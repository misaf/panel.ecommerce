## Vendra Newsletter

The `misaf/vendra-newsletter` package owns newsletter domain behavior and the Filament admin UI for newsletters and subscribers, plus the queued send pipeline, the public unsubscribe flow, and a self-registered scheduled send.

### Standards

- Keep newsletter domain code inside `packages/vendra-newsletter` using the `Misaf\VendraNewsletter` namespace.
- Use this package for models, migrations, factories, seeders, policies, permission enums, Filament resources, mail, jobs, actions, the unsubscribe controller/route, console commands, translations, config, and package bootstrapping.
- Follow existing model conventions: tenant ownership, soft deletes, typed casts, the `NewsletterStatusEnum` lifecycle (`draft` → `scheduled` → `sent`), factories, and typed query scopes (`Newsletter::due()`, `NewsletterSubscriber::subscribed()` / `unsubscribed()`).
- Tenant awareness is owned by `misaf/vendra-support` via `Misaf\VendraSupport\Support\TenantAwareness`, which derives purely from the bound `TenantResolver`. Installing a tenant provider (e.g. `misaf/vendra-tenant`) makes the app tenant-aware; without one the default null resolver keeps it disabled. The newsletter module defines no `tenant_aware` config.
- Keep the module tenant-agnostic: it must build and run with or without a tenant provider. Never reference a concrete provider such as `Misaf\VendraTenant`, `Tenant::`, or the `tenants:artisan` command anywhere — models, migrations, factories, seeders, fixtures, jobs, or commands. Let `BelongsToTenant` assign `tenant_id`; do not set it manually.
- Keep Filament resources thin by delegating forms to `Schemas/*Form.php` and tables to `Tables/*Table.php`. Keep the reusable Filament send button in `Filament/Clusters/Resources/Newsletters/Actions/SendNewsletterAction.php`; it delegates to the `Actions\SendNewsletter` domain action and is hidden once a newsletter is `sent`.

### Sending pipeline

- Orchestrate sends through the `Actions\SendNewsletter` domain action. It guards against re-sending, fans subscribed recipients out in chunks of `batch_chunk_size`, dispatches one `Jobs\SendNewsletterBatchJob` per chunk, and marks the newsletter `sent`.
- Keep the queue layers split: `SendNewsletterBatchJob` (fan-out, `queue.timeout`) dispatches per-recipient `SendNewsletterEmailJob` (`queue.email_timeout`), which skips unsubscribed recipients and delivers `Mail\NewsletterMail` (view `resources/views/mail/newsletter.blade.php`).
- Read all queue/batch settings (`batch_chunk_size`, `queue.connection`, `queue.name`, `queue.tries`, `queue.timeout`, `queue.email_timeout`) from `config/vendra-newsletter.php` through strict config accessors (`Config::integer`, `Config::string`). Rely on Spatie's tenant-aware queues to restore tenant context on the worker; do not stamp or query `tenant_id` manually.

### Scheduled sending

- `Console\Commands\SendScheduledNewslettersCommand` dispatches due newsletters and wraps its query in `TenantResolver::eachTenant()` so each tenant's subscribers stay correctly scoped. The support contract owns the "run for every tenant" concern; never enumerate tenants here.
- The service provider self-registers the schedule via `callAfterResolving(Schedule::class, …)`, gated by config: skip when `schedule.enabled` is false, and use `schedule.cron` for the cadence. Scheduling lives in the package, not the host app.

### Unsubscribe

- Keep the public opt-out in `Http\Controllers\NewsletterUnsubscribeController` + `routes/web.php` (`vendra-newsletter.unsubscribe`) + `resources/views/unsubscribe.blade.php`. Resolve the subscriber by its `unsubscribe_token`; the current tenant is resolved from the request domain, so no tenant handling belongs in the controller.

### Conventions

- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic. Match the surrounding file and do not add comments that restate the code.
- Add or update Pest tests for policy coverage, config/navigation/schedule behavior, translation parity, model contracts, the send pipeline, per-tenant isolation, and the unsubscribe flow.
- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus a tenant-agnostic expectation, e.g. `arch()->expect('Misaf\VendraNewsletter')->not->toUse('Misaf\VendraTenant')`.
