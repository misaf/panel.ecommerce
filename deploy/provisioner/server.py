import base64
import binascii
import hmac
import json
import os
import re
import subprocess
import threading
from http.server import BaseHTTPRequestHandler, ThreadingHTTPServer
from pathlib import Path
from typing import Any

DEPLOY_ROOT = Path(os.environ.get("DEPLOY_ROOT", "/workspace")).resolve()
DEPLOY_DIR = DEPLOY_ROOT / "deploy"
PROPERTIES_DIR = DEPLOY_DIR / "properties"
STACK = DEPLOY_DIR / "stack.sh"
TOKEN = os.environ.get("PROVISIONER_TOKEN") or os.environ.get(
    "STOREFRONT_PROVISIONER_TOKEN", ""
)
DEFAULT_IMAGE = os.environ.get("STOREFRONT_IMAGE", "")
SLUG_PATTERN = re.compile(r"^[a-z0-9]+(?:-[a-z0-9]+)*$")
DOMAIN_PATTERN = re.compile(
    r"^(?=.{1,253}$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$"
)
PROVISION_LOCK = threading.Lock()


class ProvisioningError(Exception):
    pass


def require_string(payload: dict[str, Any], key: str) -> str:
    value = payload.get(key)
    if not isinstance(value, str) or not value.strip():
        raise ProvisioningError(f"{key} must be a non-empty string")
    return value.strip()


def validate_configuration(encoded: str, slug: str, domain: str) -> None:
    try:
        decoded = base64.b64decode(encoded, validate=True).decode("utf-8")
        configuration = json.loads(decoded)
    except (binascii.Error, UnicodeDecodeError, json.JSONDecodeError) as error:
        raise ProvisioningError("configuration_base64 is invalid") from error

    if not isinstance(configuration, dict):
        raise ProvisioningError("configuration must decode to an object")
    if configuration.get("slug") != slug or configuration.get("domain") != domain:
        raise ProvisioningError("configuration slug/domain do not match the request")
    if configuration.get("theme") != "default":
        raise ProvisioningError("only the default storefront theme is supported")


def upsert_env(path: Path, values: dict[str, str]) -> None:
    lines = path.read_text(encoding="utf-8").splitlines() if path.exists() else []
    remaining = dict(values)
    updated: list[str] = []

    for line in lines:
        key = line.split("=", 1)[0] if "=" in line else ""
        if key in remaining:
            updated.append(f"{key}={remaining.pop(key)}")
        else:
            updated.append(line)

    if updated and updated[-1] != "":
        updated.append("")
    updated.extend(f"{key}={value}" for key, value in remaining.items())
    path.write_text("\n".join(updated) + "\n", encoding="utf-8")


def run(command: list[str], cwd: Path = DEPLOY_ROOT) -> None:
    result = subprocess.run(
        command,
        cwd=cwd,
        check=False,
        capture_output=True,
        text=True,
        timeout=180,
    )
    if result.returncode != 0:
        detail = (result.stderr or result.stdout).strip()[-2000:]
        raise ProvisioningError(detail or f"command failed: {' '.join(command)}")


def provision(payload: dict[str, Any]) -> dict[str, str]:
    slug = require_string(payload, "slug").lower()
    domain = require_string(payload, "domain").lower()
    encoded = require_string(payload, "configuration_base64")
    image = str(payload.get("image") or DEFAULT_IMAGE).strip()

    if not SLUG_PATTERN.fullmatch(slug):
        raise ProvisioningError("slug must contain lowercase letters, digits, and hyphens")
    if not DOMAIN_PATTERN.fullmatch(domain):
        raise ProvisioningError("domain is invalid")
    if not image.startswith("ghcr.io/"):
        raise ProvisioningError("image must be hosted on ghcr.io")

    validate_configuration(encoded, slug, domain)
    property_dir = PROPERTIES_DIR / slug

    if not property_dir.exists():
        run([str(STACK), "property", "add", slug, domain, image])

    env_path = property_dir / ".env"
    upsert_env(
        env_path,
        {
            "DOMAIN": domain,
            "ROUTER_NAME": slug,
            "STOREFRONT_IMAGE": image,
            "STOREFRONT_CONFIG_BASE64": encoded,
        },
    )

    run(["docker", "compose", "-p", slug, "pull", "web"], property_dir)
    run(["docker", "compose", "-p", slug, "up", "-d", "--remove-orphans"], property_dir)

    digest = image.split("@", 1)[1] if "@sha256:" in image else ""
    return {
        "status": "ready",
        "reference": slug,
        "image_digest": digest,
    }


class Handler(BaseHTTPRequestHandler):
    server_version = "VendraProvisioner/1.0"

    def send_json(self, status: int, payload: dict[str, str]) -> None:
        body = json.dumps(payload).encode("utf-8")
        self.send_response(status)
        self.send_header("Content-Type", "application/json")
        self.send_header("Content-Length", str(len(body)))
        self.end_headers()
        self.wfile.write(body)

    def do_GET(self) -> None:
        if self.path == "/health":
            self.send_json(200, {"status": "ok"})
            return
        self.send_json(404, {"error": "not found"})

    def do_POST(self) -> None:
        if self.path != "/storefronts":
            self.send_json(404, {"error": "not found"})
            return

        supplied = self.headers.get("Authorization", "")
        expected = f"Bearer {TOKEN}"
        if not TOKEN or not hmac.compare_digest(supplied, expected):
            self.send_json(401, {"error": "unauthorized"})
            return

        try:
            length = int(self.headers.get("Content-Length", "0"))
            if length <= 0 or length > 1_048_576:
                raise ProvisioningError("request body size is invalid")
            payload = json.loads(self.rfile.read(length))
            if not isinstance(payload, dict):
                raise ProvisioningError("request body must be an object")
            with PROVISION_LOCK:
                result = provision(payload)
            self.send_json(200, result)
        except (ProvisioningError, json.JSONDecodeError) as error:
            self.send_json(422, {"error": str(error)})
        except Exception:
            self.send_json(500, {"error": "provisioning failed"})

    def log_message(self, message: str, *args: Any) -> None:
        print(f"{self.address_string()} - {message % args}", flush=True)


if __name__ == "__main__":
    if not TOKEN:
        raise SystemExit("PROVISIONER_TOKEN is required")
    ThreadingHTTPServer(("0.0.0.0", 8080), Handler).serve_forever()
