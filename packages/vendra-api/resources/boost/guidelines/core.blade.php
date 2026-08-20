## Vendra API

The `misaf/vendra-api` package provides shared API Platform for Laravel infrastructure — reusable `ApiResource` DTOs such as `ResourceReference` and service-provider wiring — consumed by the per-domain API modules (`misaf/vendra-*-api`).

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

### Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

- Keep shared API code inside `packages/vendra-api` using the `Misaf\VendraApi` namespace.
- Use this package for cross-cutting API Platform building blocks: shared `ApiResource` DTOs such as `ResourceReference` and API service-provider wiring. Domain resources, state providers, and routes belong in the per-domain API modules, not here.
- Keep every domain API localization-package agnostic: do not require `misaf/vendra-localization` or attach `vendra.locale` inside API packages. Locale-aware behavior may read Laravel's current locale, while the host application decides whether and how to resolve it.
- Depend only on framework and API Platform packages. Never import a domain module or a concrete tenant provider such as `Misaf\VendraTenant`; this module stays domain- and tenant-agnostic.
### Write-side architecture (command/query seam)

- Reads and writes are already split the CQRS-lite way: API Platform **State Providers** are the read/query side, **State Processors** are the write/command side. Keep formal CQRS infrastructure (a command/query bus, separate read models, `*Handler` classes) out — it is not needed here.
- **Mappers compose shared concerns, they do not copy each other.** A `ResourceMapper` gets its guard and its related-record references from `Misaf\VendraApi\State\Concerns\MapsResourceReferences` (`expectModel()`, `referenceTo()`, `referencesTo()`) and its media list from `Misaf\VendraMultimediaApi\State\Concerns\MapsPublicMultimedia` (`publicMultimedia()`, with `onlyWhenLoaded: true` on category mappers so a collection does not query per row). The multimedia concern lives in `vendra-multimedia-api` because that package already depends on `vendra-api`; putting it the other way round would be a cycle. Copying a sibling package's mapper is how the resources drifted apart — the custom-page resource lost its timestamps that way.
- **A `LinksHandler` selects only the columns its mapper reads.** A category rendered as a `ResourceReference` needs `id,name`; selecting the whole row costs on every page of a collection.
- **Processors are thin adapters.** A `ProcessorInterface` implementation maps the resource DTO to domain inputs and calls exactly one domain **Action**'s `execute()`. No persistence, `DB` transactions, or business rules live in a Processor — those belong in the Action, so the API and Filament panels invoke the same command path. See `Misaf\VendraAffiliateApi\State\RecordReferralVisitProcessor` delegating to `Misaf\VendraAffiliate\Actions\RecordAffiliateClickAction`, and the arch rules that enforce it in that package's `tests/ArchTest.php`.
- Domain write logic lives in single-purpose Action classes named with the `-Action` suffix and a single `execute()` method (e.g. `CreateTransactionAction`, `SubscribeAction`). Reserve the `-Action` suffix for Filament UI action factories only when they build a `Filament\Actions\Action`; those use `-TableAction`/`-PageAction` to stay distinct from domain Actions.
- **Do not add a command bus** until a concrete need appears: writes that must be queued/async, or a cross-cutting concern (logging, transactions, auth) that must wrap every command uniformly. When that day comes, use Laravel's built-in `Illuminate\Bus` + queued jobs (an Action can implement `ShouldQueue` or be wrapped by a job) — no new dependency.

- Keep shared DTOs reusable across resource types, and keep their behavior covered by focused tests.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic. Match the surrounding file and do not add comments that restate the code.
- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus `arch()->expect('Misaf\VendraApi')->not->toUse('Misaf\VendraTenant')`.
