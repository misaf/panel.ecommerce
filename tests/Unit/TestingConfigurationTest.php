<?php

declare(strict_types=1);

it('discards expected exception reports while running tests', function (): void {
    expect(config('logging.default'))->toBe('testing')
        ->and(config('logging.channels.testing.handler'))->toBe(Monolog\Handler\NullHandler::class);
});

it('runs the host test script in parallel without changing diagnostic scripts', function (): void {
    $manifest = json_decode(
        file_get_contents(base_path('composer.json')),
        true,
        flags: JSON_THROW_ON_ERROR,
    );
    $testCommand = implode(' ', (array) ($manifest['scripts']['test'] ?? []));

    expect($testCommand)->toContain('--parallel');

    foreach ($manifest['scripts'] ?? [] as $scriptName => $commands) {
        if (1 !== preg_match('/coverage|profil|mutation|benchmark/i', $scriptName)) {
            continue;
        }

        expect(implode(' ', (array) $commands))->not->toContain('--parallel');
    }
});
