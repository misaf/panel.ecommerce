---
name: vendra-inquiry-api-development
description: "Create, modify, review, or test the Vendra Inquiry API module in packages/vendra-inquiry-api. Use for InquiryResource, SubmitInquiryProcessor, SubmitInquiryRequest, the /support/inquiries endpoint, the storefront contact form API, inquiry MCP tools, or contact-form validation rules."
---

# Vendra Inquiry API

## Workflow

- Inspect `composer.json`, sibling files, and existing tests before changing the package.
- Use Laravel Boost `application-info` and `search-docs` before code changes.
- Apply `laravel-best-practices` to Laravel PHP and `pest-testing` whenever tests change.
- Keep changes inside this package's boundary and preserve its public contracts.
- Add or update focused Pest coverage, then run `php artisan test --compact --testsuite=vendra-inquiry-api` and `composer stan`.

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

## Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

Use this skill with `laravel-best-practices` for Laravel PHP and `pest-testing` when tests change.

## Module Boundary

Treat `packages/vendra-inquiry-api` as the HTTP and MCP surface of `misaf/vendra-inquiry`.

- Use namespace `Misaf\VendraInquiryApi`.
- Keep DTOs in `src/ApiResource`, processors in `src/State`, and validation rules in `src/Http/Requests`.
- Keep persistence, status transitions, permissions, and the inbox in `misaf/vendra-inquiry`.
- Keep orders, carts, deliveries, and catalog out entirely.
- Keep dependencies explicit and request approval before introducing new packages.

## Domain Standards

- Keep submission unauthenticated and throttled; a shop that cannot be written to is not a shop.
- Answer `204` with `output: false`: nothing about an enquiry belongs back in the response.
- Delegate the write to `SubmitInquiryAction` so console, HTTP, and tests share one definition of a valid enquiry.
- Take `source` and the sender's locale from the request, never the payload.
- Use final classes, explicit types, PHPDoc generics, and `declare(strict_types=1)`.

## Testing And Verification

- Cover a successful submission, each validation failure, and that the stored message matches what was sent.
- Assert the endpoint stays unauthenticated and that no read operation is exposed.
- Run `php artisan test --compact packages/vendra-inquiry-api/tests`.
- Run `vendor/bin/pint --dirty --format agent` after changing PHP files.
