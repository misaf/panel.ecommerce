@php
/** @var \Laravel\Boost\Install\GuidelineAssist $assist */
@endphp
# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the smallest relevant test scope first, then expand to broader suites only when necessary.
- Use `{{ $assist->artisanCommand('test --parallel') }}` by default for targeted tests and full suites to minimize feedback time.
- Omit `--parallel` only when debugging a failure, investigating race conditions or concurrency issues, using shared mutable state or external resources that cannot be isolated, or when the execution environment does not support parallel testing.
- Keep intentionally non-parallel coverage, profiling, mutation testing, and benchmarking commands unchanged unless parallel execution is clearly safe.
