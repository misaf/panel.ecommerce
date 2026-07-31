#!/usr/bin/env bash
#
# One entrypoint for the local reverse-proxy stack. Works from any directory.
# Prefer the wrapper: `bin/stack <command>`.
#
#   bin/stack up [--build]        # network + proxy + vendra
#   bin/stack down                # stop properties + vendra + proxy
#   bin/stack restart             # down then up
#   bin/stack status              # health-check the whole stack
#   bin/stack logs [target]       # follow logs (default: vendra php; also proxy|<svc>|<property>)
#   bin/stack open <target>       # open a URL in the browser
#   bin/stack ps                  # docker status of proxy + vendra + properties
#   bin/stack urls                # print the local URLs
#   bin/stack hosts [--write]     # print (or append) the /etc/hosts entries
#
#   bin/stack property add <slug> <domain> [image]   # scaffold a storefront
#   bin/stack property up|down|restart|rm <slug>     # manage one property
#   bin/stack property ls                            # list properties
#
# Properties are scaffolded from property-template/ into properties/<slug>/ (which
# is gitignored — nothing property-specific is committed).
#
set -euo pipefail

PROG="bin/stack"
DEPLOY_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_DIR="$(cd "$DEPLOY_DIR/.." && pwd)"
PROXY_DIR="$DEPLOY_DIR/proxy"
VENDRA_DIR="$DEPLOY_DIR/vendra"
TEMPLATE_DIR="$DEPLOY_DIR/property-template"
PROPERTIES_DIR="$DEPLOY_DIR/properties"
BASE_COMPOSE="$REPO_DIR/docker/docker-compose.yml"
NETWORK="traefik-public"

c_green=$'\033[32m'; c_dim=$'\033[2m'; c_red=$'\033[31m'; c_yellow=$'\033[33m'; c_off=$'\033[0m'
info() { printf '%s➜%s %s\n' "$c_green" "$c_off" "$*"; }
warn() { printf '%s!%s %s\n' "$c_red" "$c_off" "$*" >&2; }

base_domain() {
  local b; b="$(grep -E '^BASE_DOMAIN=' "$PROXY_DIR/.env" 2>/dev/null | head -1 | cut -d= -f2 | tr -d ' \r')"
  echo "${b:-vendra.test}"
}

env_value() { grep -E "^$2=" "$1" 2>/dev/null | head -1 | cut -d= -f2- | tr -d '\r'; }

# Ensure a project has a .env (copy from .env.example on first run).
ensure_env() {
  local dir="$1"
  if [[ ! -f "$dir/.env" && -f "$dir/.env.example" ]]; then
    cp "$dir/.env.example" "$dir/.env"
    info "created $dir/.env from .env.example — review it before production"
  fi
}

proxy() { ( cd "$PROXY_DIR" && docker compose "$@" ); }

# Vendra backend. -p pins the name; explicit -f lists both files; --env-file is
# REQUIRED because with two -f files Compose otherwise loads interpolation vars
# (${BASE_DOMAIN}…) from the first file's dir (docker/), leaving router rules
# like Host(`admin.`) with an empty domain.
vendra() {
  ( cd "$VENDRA_DIR" \
    && docker compose -p vendra --env-file .env \
         -f "$BASE_COMPOSE" -f docker-compose.traefik.yml "$@" )
}

# docker compose for one scaffolded property (its own project).
property_compose() {
  local slug="$1"; shift
  ( cd "$PROPERTIES_DIR/$slug" && docker compose -p "$slug" "$@" )
}

ensure_network() {
  if ! docker network inspect "$NETWORK" >/dev/null 2>&1; then
    docker network create "$NETWORK" >/dev/null
    info "created docker network $NETWORK"
  fi
}

# Traefik needs a default cert or TLS fails outright. Generate a self-signed
# fallback covering the base domain, its wildcards, and every scaffolded property
# domain. Pass "force" to regenerate (e.g. after adding/removing a property).
# mkcert is the trusted upgrade — see the README.
generate_local_cert() {
  local force="${1:-}" dir="$PROXY_DIR/traefik/certs" base sans env d
  base="$(base_domain)"
  if [[ -z "$force" && -s "$dir/local.pem" && -s "$dir/local-key.pem" ]]; then return; fi
  command -v openssl >/dev/null || { warn "openssl missing — TLS will fail; install mkcert or openssl"; return; }
  sans="DNS:${base},DNS:*.${base},DNS:*.admin.${base}"
  if [[ -d "$PROPERTIES_DIR" ]]; then
    for env in "$PROPERTIES_DIR"/*/.env; do
      [[ -f "$env" ]] || continue
      d="$(env_value "$env" DOMAIN)"
      [[ -n "$d" ]] && sans="${sans},DNS:${d},DNS:www.${d}"
    done
  fi
  openssl req -x509 -newkey rsa:2048 -nodes -days 3650 \
    -keyout "$dir/local-key.pem" -out "$dir/local.pem" \
    -subj "/CN=${base}" -addext "subjectAltName=${sans}" >/dev/null 2>&1 \
    && info "generated self-signed dev cert (run mkcert for a trusted one — see README)"
}

# If the proxy is running, restart it so a freshly generated cert is served.
reload_proxy_cert() {
  if docker ps --format '{{.Names}}' | grep -q '^proxy-traefik-1$'; then
    proxy restart >/dev/null 2>&1 && info "reloaded proxy to pick up the new cert"
  fi
}

cmd_up() {
  command -v docker >/dev/null || { warn "docker not found / not running"; exit 1; }
  ensure_network
  ensure_env "$PROXY_DIR"; ensure_env "$VENDRA_DIR"
  generate_local_cert
  info "starting proxy (Traefik)…";   proxy up -d
  info "starting Vendra backend…"
  if [[ "${1:-}" == "--build" ]]; then
    vendra up -d --build
  else
    vendra up -d
  fi
  echo; cmd_urls
}

cmd_down() {
  if [[ -d "$PROPERTIES_DIR" ]]; then
    for d in "$PROPERTIES_DIR"/*/; do
      [[ -f "${d}docker-compose.yml" ]] || continue
      local slug; slug="$(basename "$d")"
      info "stopping property ${slug}…"; property_compose "$slug" down || true
    done
  fi
  info "stopping Vendra backend…"; vendra down || true
  info "stopping proxy…";          proxy down || true
}

cmd_urls() {
  local base; base="$(base_domain)"
  cat <<EOF
${c_dim}Local URLs (accept the cert warning, or install mkcert — see README):${c_off}
  admin      https://admin.${base}
  console    https://console.${base}
  reseller   https://reseller.${base}
  api        https://api.${base}/up
  dashboard  https://traefik.${base}
EOF
  if [[ -d "$PROPERTIES_DIR" ]]; then
    for env in "$PROPERTIES_DIR"/*/.env; do
      [[ -f "$env" ]] || continue
      printf '  property   https://%s\n' "$(env_value "$env" DOMAIN)"
    done
  fi
}

# ---- logs / open ------------------------------------------------------------

cmd_logs() {
  local target="${1:-php}"
  case "$target" in
    proxy)  proxy logs -f ;;
    vendra) vendra logs -f ;;
    *)
      if [[ -d "$PROPERTIES_DIR/$target" ]]; then
        property_compose "$target" logs -f
      else
        vendra logs -f "$target"   # a vendra service, e.g. php|horizon|mysql
      fi ;;
  esac
}

cmd_open() {
  local t="${1:-admin}" base url opener; base="$(base_domain)"
  case "$t" in
    admin)              url="https://admin.${base}";;
    api)                url="https://api.${base}/up";;
    console)            url="https://console.${base}";;
    reseller)           url="https://reseller.${base}";;
    dashboard|traefik)  url="https://traefik.${base}";;
    *)
      if [[ -f "$PROPERTIES_DIR/$t/.env" ]]; then
        url="https://$(env_value "$PROPERTIES_DIR/$t/.env" DOMAIN)"
      else
        warn "unknown target '$t' (admin|api|console|reseller|dashboard|<property>)"; exit 1
      fi ;;
  esac
  opener="$(command -v open || command -v xdg-open || true)"
  [[ -n "$opener" ]] || { warn "no opener found; URL is: $url"; exit 1; }
  info "opening $url"; "$opener" "$url"
}

# ---- hosts ------------------------------------------------------------------

# Emit every hostname the stack serves locally (base panels + property domains).
hosts_list() {
  local base env slug d; base="$(base_domain)"
  printf '%s\n' "$base" "admin.$base" "console.$base" "reseller.$base" \
                "api.$base" "traefik.$base" "acme.admin.$base"
  if [[ -d "$PROPERTIES_DIR" ]]; then
    for env in "$PROPERTIES_DIR"/*/.env; do
      [[ -f "$env" ]] || continue
      slug="$(basename "$(dirname "$env")")"; d="$(env_value "$env" DOMAIN)"
      [[ -n "$d" ]] && printf '%s\n' "$d" "www.$d" "$slug.admin.$base"
    done
  fi
}

host_present() {   # is $1 already mapped in /etc/hosts?
  local escaped="${1//./\\.}"
  grep -qE "^[^#]*[[:space:]]${escaped}([[:space:]]|\$)" /etc/hosts 2>/dev/null
}

cmd_hosts() {
  local write="" name missing=""
  [[ "${1:-}" == "--write" ]] && write=1
  for name in $(hosts_list); do
    if host_present "$name"; then continue; fi
    missing="${missing:+$missing }$name"
  done
  if [[ -z "$missing" ]]; then
    info "all stack hosts already resolve in /etc/hosts"; return
  fi
  if [[ -n "$write" ]]; then
    printf '127.0.0.1 %s\n' "$missing" | sudo tee -a /etc/hosts >/dev/null
    info "added to /etc/hosts: $missing"
  else
    echo "# add to /etc/hosts (or run: $PROG hosts --write):"
    printf '127.0.0.1 %s\n' "$missing"
  fi
}

# ---- status -----------------------------------------------------------------

STATUS_FAIL=0
_ok()   { printf '  %s✓%s %-24s %s\n' "$c_green"  "$c_off" "$1" "${2:-}"; }
_bad()  { printf '  %s✗%s %-24s %s\n' "$c_red"    "$c_off" "$1" "${2:-}"; STATUS_FAIL=1; }
_warn() { printf '  %s!%s %-24s %s\n' "$c_yellow" "$c_off" "$1" "${2:-}"; }
_http() { curl -ks -o /dev/null -w '%{http_code}' --max-time 5 --resolve "${2}:443:127.0.0.1" "$1" 2>/dev/null || echo 000; }
_cstate()  { docker inspect -f '{{.State.Status}}' "$1" 2>/dev/null || echo absent; }
_chealth() { docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}{{.State.Status}}{{end}}' "$1" 2>/dev/null || echo absent; }

cmd_status() {
  local base; base="$(base_domain)"
  echo "Vendra stack — status (base: ${base})"

  if docker info >/dev/null 2>&1; then _ok "docker" "running"; else _bad "docker" "not running"; echo; return; fi
  if docker network inspect "$NETWORK" >/dev/null 2>&1; then _ok "network" "$NETWORK"; else _bad "network" "missing — run '$PROG up'"; fi

  local ph; ph="$(_chealth proxy-traefik-1)"
  case "$ph" in
    healthy|running) _ok "proxy (traefik)" "$ph";;
    absent)          _bad "proxy (traefik)" "not started";;
    *)               _warn "proxy (traefik)" "$ph";;
  esac

  # TLS listener reachable at all?
  if [[ "$(curl -ks -o /dev/null -w '%{http_code}' --max-time 5 https://127.0.0.1 2>/dev/null || echo 000)" != "000" ]]; then
    _ok "ports 80/443" "listening"
  else
    _bad "ports 80/443" "no TLS listener (is proxy up? is 443 free?)"
  fi

  if [[ -s "$PROXY_DIR/traefik/certs/local.pem" ]]; then
    local n; n="$(openssl x509 -in "$PROXY_DIR/traefik/certs/local.pem" -noout -text 2>/dev/null | grep -c 'DNS:')"
    _ok "dev cert" "present"
  else
    _warn "dev cert" "none (mkcert or prod ACME expected)"
  fi

  local svc st
  for svc in php mysql redis horizon; do
    st="$(_chealth "vendra-${svc}-1")"
    case "$st" in
      healthy|running) _ok "vendra-${svc}" "$st";;
      absent)          _bad "vendra-${svc}" "not started";;
      *)               _warn "vendra-${svc}" "$st";;
    esac
  done

  # Endpoint reachability (resolve to loopback so /etc/hosts gaps don't hide it).
  local code
  code="$(_http "https://api.${base}/up" "api.${base}")"
  [[ "$code" == "200" ]] && _ok "api.${base}/up" "$code" || _bad "api.${base}/up" "$code (want 200)"
  code="$(_http "https://console.${base}/login" "console.${base}")"
  [[ "$code" == "200" ]] && _ok "console.${base}" "$code" || _bad "console.${base}" "$code (want 200)"
  code="$(_http "https://reseller.${base}/login" "reseller.${base}")"
  [[ "$code" == "200" ]] && _ok "reseller.${base}" "$code" || _bad "reseller.${base}" "$code (want 200)"
  code="$(_http "https://admin.${base}/login" "admin.${base}")"
  case "$code" in
    404)     _ok  "admin.${base}" "$code (no bare-host tenant — expected)";;
    200|302) _ok  "admin.${base}" "$code";;
    *)       _bad "admin.${base}" "$code";;
  esac

  if [[ -d "$PROPERTIES_DIR" ]]; then
    local env d slug
    for env in "$PROPERTIES_DIR"/*/.env; do
      [[ -f "$env" ]] || continue
      slug="$(basename "$(dirname "$env")")"; d="$(env_value "$env" DOMAIN)"
      code="$(_http "https://${d}/" "$d")"
      [[ "$code" =~ ^[23] ]] && _ok "property ${slug}" "$code ($d)" || _warn "property ${slug}" "$code ($d — started?)"
    done
  fi

  echo
  [[ "$STATUS_FAIL" -eq 0 ]] && info "all green" || warn "some checks failed (see ✗ above)"
  return "$STATUS_FAIL"
}

# ---- property management ----------------------------------------------------

property_add() {
  local slug="${1:-}" domain="${2:-}" image="${3:-}" dir
  [[ -n "$slug" && -n "$domain" ]] || { warn "usage: property add <slug> <domain> [image]"; exit 1; }
  [[ "$slug" =~ ^[a-z0-9-]+$ ]]    || { warn "slug must be lowercase letters, digits, hyphens"; exit 1; }
  dir="$PROPERTIES_DIR/$slug"
  [[ -e "$dir" ]] && { warn "property '$slug' already exists at $dir"; exit 1; }
  mkdir -p "$dir"
  cp "$TEMPLATE_DIR/docker-compose.yml" "$dir/docker-compose.yml"
  cat > "$dir/.env" <<EOF
# Storefront property "${slug}" — scaffolded by $PROG. Edit and re-run.
DOMAIN=${domain}
ROUTER_NAME=${slug}
BASE_DOMAIN=$(base_domain)
STOREFRONT_IMAGE=${image:-REPLACE_WITH_YOUR_NEXTJS_IMAGE}
# Port the storefront image listens on (Next.js = 3000).
STOREFRONT_PORT=3000
# Local dev: empty (mkcert/self-signed). Production: letsencrypt
CERT_RESOLVER=
EOF
  info "created property '${slug}' → ${domain}  ($dir)"
  generate_local_cert force
  reload_proxy_cert
  echo "  next:"
  [[ -z "$image" ]] && echo "    • set STOREFRONT_IMAGE in ${dir}/.env"
  echo "    • $PROG hosts --write   (adds ${domain} to /etc/hosts)"
  echo "    • $PROG property up ${slug}"
}

property_up() {
  local slug="${1:-}"
  [[ -n "$slug" && -d "$PROPERTIES_DIR/$slug" ]] || { warn "no such property: '${slug:-}'"; exit 1; }
  if grep -q '^STOREFRONT_IMAGE=REPLACE_WITH' "$PROPERTIES_DIR/$slug/.env"; then
    warn "set STOREFRONT_IMAGE in $PROPERTIES_DIR/$slug/.env before starting"; exit 1
  fi
  ensure_network
  info "starting property ${slug}…"; property_compose "$slug" up -d
}

property_down() {
  local slug="${1:-}"
  [[ -n "$slug" && -d "$PROPERTIES_DIR/$slug" ]] || { warn "no such property: '${slug:-}'"; exit 1; }
  property_compose "$slug" down
}

property_restart() {
  local slug="${1:-}"
  [[ -n "$slug" && -d "$PROPERTIES_DIR/$slug" ]] || { warn "no such property: '${slug:-}'"; exit 1; }
  property_compose "$slug" down || true
  property_up "$slug"
}

property_rm() {
  local slug="${1:-}"
  [[ -n "$slug" && -d "$PROPERTIES_DIR/$slug" ]] || { warn "no such property: '${slug:-}'"; exit 1; }
  if [[ -t 0 && "${2:-}" != "--yes" && "${2:-}" != "-y" ]]; then
    printf 'Remove property %s (stops container, deletes %s)? [y/N] ' "$slug" "$PROPERTIES_DIR/$slug"
    local ans; read -r ans; [[ "$ans" =~ ^[Yy]$ ]] || { info "aborted"; return; }
  fi
  property_compose "$slug" down >/dev/null 2>&1 || true
  rm -rf "${PROPERTIES_DIR:?}/${slug}"
  info "removed property ${slug}"
  generate_local_cert force
  reload_proxy_cert
}

property_list() {
  [[ -d "$PROPERTIES_DIR" ]] || { echo "(no properties yet — $PROG property add <slug> <domain> [image])"; return; }
  local any="" env slug domain
  for env in "$PROPERTIES_DIR"/*/.env; do
    [[ -f "$env" ]] || continue
    any=1; slug="$(basename "$(dirname "$env")")"; domain="$(env_value "$env" DOMAIN)"
    printf '  %-20s %s\n' "$slug" "$domain"
  done
  [[ -z "$any" ]] && echo "(no properties yet — $PROG property add <slug> <domain> [image])"
}

cmd_property() {
  local sub="${1:-}"; shift || true
  case "$sub" in
    add)      property_add "$@";;
    up)       property_up "$@";;
    down)     property_down "$@";;
    restart)  property_restart "$@";;
    rm)       property_rm "$@";;
    ls|list)  property_list;;
    *) warn "usage: property {add <slug> <domain> [image] | up|down|restart|rm <slug> | ls}"; exit 1;;
  esac
}

usage() {
  cat <<EOF
Usage: $PROG <command>

  up [--build]                     network + proxy + vendra, then print URLs
  down                             stop properties + vendra + proxy
  restart [--build]                down then up
  status                           health-check the whole stack
  logs [target]                    follow logs (default vendra php; also proxy|<svc>|<property>)
  open <target>                    open a URL (admin|api|console|reseller|dashboard|<property>)
  ps                               docker status of proxy + vendra + properties
  urls                             print local URLs
  hosts [--write]                  print (or append) the /etc/hosts entries

  property add <slug> <domain> [image]   scaffold a storefront property
  property up <slug>                     start a property
  property down <slug>                   stop a property
  property restart <slug>                restart a property
  property rm <slug> [--yes]             stop + delete a property, refresh cert
  property ls                            list properties
EOF
}

# ---- dispatch ---------------------------------------------------------------

case "${1:-}" in
  up)       shift; cmd_up "${1:-}";;
  down)     cmd_down;;
  restart)  cmd_down; cmd_up "${2:-}";;
  status)   cmd_status;;
  logs)     cmd_logs "${2:-php}";;
  open)     cmd_open "${2:-admin}";;
  ps)       info "proxy:"; proxy ps; echo; info "vendra:"; vendra ps; echo; info "properties:"; property_list;;
  urls)     cmd_urls;;
  hosts)    cmd_hosts "${2:-}";;
  property) shift; cmd_property "$@";;
  help|-h|--help) usage;;
  *) usage; exit 1;;
esac
