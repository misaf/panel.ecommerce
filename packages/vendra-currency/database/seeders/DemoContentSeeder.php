<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Database\Seeders;

use Illuminate\Support\Facades\Validator;
use Misaf\VendraCurrency\Models\Currency;
use Misaf\VendraCurrency\Support\CurrencyRegistry;
use Misaf\VendraSupport\Database\Seeders\DemoContentSeeder as BaseDemoContentSeeder;

final class DemoContentSeeder extends BaseDemoContentSeeder
{
    protected function seedFactories(): void
    {
        $this->currentTenantOrNull();

        $this->seedFixtures($this->demoRecords());
    }

    /**
     * @param list<array<string, mixed>> $records
     */
    protected function seedFixtures(array $records): void
    {
        $this->currentTenantOrNull();

        foreach ($records as $record) {
            $this->handleSeedFixtureRecord($this->validatedFixtureRecord($record));
        }
    }

    /**
     * @return list<array{code: string, symbol: string|null, status: bool, is_default: bool}>
     */
    private function demoRecords(): array
    {
        return [
            ['code' => 'USD', 'symbol' => '$', 'status' => true, 'is_default' => true],
            ['code' => 'EUR', 'symbol' => '€', 'status' => true, 'is_default' => false],
            ['code' => 'IRR', 'symbol' => '﷼', 'status' => true, 'is_default' => false],
            ['code' => 'BTC', 'symbol' => '₿', 'status' => true, 'is_default' => false],
        ];
    }

    /**
     * @param array{code: string, symbol: string|null, status: bool, is_default: bool} $data
     */
    private function handleSeedFixtureRecord(array $data): void
    {
        Currency::create([
            'code'           => $data['code'],
            'name'           => CurrencyRegistry::nameFor($data['code']),
            'symbol'         => $data['symbol'],
            'decimal_places' => CurrencyRegistry::minorUnitFor($data['code']) ?? 2,
            'type'           => CurrencyRegistry::typeFor($data['code']),
            'status'         => $data['status'],
            'is_default'     => $data['is_default'],
        ]);
    }

    /**
     * @param array<string, mixed> $record
     *
     * @return array{code: string, symbol: string|null, status: bool, is_default: bool}
     */
    private function validatedFixtureRecord(array $record): array
    {
        /** @var array{code: string, symbol: string|null, status: bool, is_default: bool} $validated */
        $validated = Validator::make(
            data: $record,
            rules: [
                'code'       => ['required', 'string', 'in:' . implode(',', CurrencyRegistry::codes())],
                'symbol'     => ['present', 'nullable', 'string'],
                'status'     => ['required', 'boolean'],
                'is_default' => ['required', 'boolean'],
            ],
        )->validate();

        return $validated;
    }
}
