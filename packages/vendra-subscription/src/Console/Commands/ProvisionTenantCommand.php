<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Misaf\VendraSubscription\Actions\CreateAccountAction;
use Misaf\VendraSubscription\Actions\ProvisionTenantAction;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraTenant\Models\TenantDomain;

final class ProvisionTenantCommand extends Command implements PromptsForMissingInput
{
    protected $signature = 'vendra-subscription:provision
        {name : Tenant name}
        {domain : Tenant domain}
        {username : Username for the tenant owner}
        {email : Email address for the tenant owner}
        {--if-missing : Skip provisioning when the tenant domain already exists}
        {--password= : Password for the tenant owner (random when omitted)}
        {--account= : Attach the website to an existing account (id or slug)}
        {--plan= : Create an account for this website subscribed to the given plan (id or slug)}
        {--seed : Run default tenant seeders after provisioning}';

    protected $description = 'Provision a website (tenant) with a domain, owner user, and role assignment';

    public function __construct(
        private readonly ProvisionTenantAction $provisionTenantAction,
        private readonly CreateAccountAction $createAccountAction,
    ) {
        parent::__construct();
    }

    /**
     * @return array<string, string|array<int, string>>
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name'     => ['Tenant name', 'Acme'],
            'domain'   => ['Tenant domain', 'acme.test'],
            'username' => ['Username for the tenant owner', 'admin_acme'],
            'email'    => ['Email address for the tenant owner', 'admin@acme.test'],
        ];
    }

    public function handle(): int
    {
        if ($this->shouldSkipExistingTenant()) {
            return self::SUCCESS;
        }

        $data = $this->validatedInput();

        if (null === $data) {
            return self::FAILURE;
        }

        $shouldSeed = $this->shouldSeedTenant();
        $password = $this->validatedPassword();

        if (false === $password) {
            return self::FAILURE;
        }

        $account = $this->resolveAccount($data['name']);

        if (false === $account) {
            return self::FAILURE;
        }

        $result = $this->provisionTenantAction->execute($data, $shouldSeed, $password, $account);

        $this->info('Website provisioned.');
        $this->table(['Field', 'Value'], [
            ['Domain', $data['domain']],
            ['Account', null === $account ? '[none]' : $account->name],
            ['Username', $result['user']->username],
            ['Email', $result['user']->email],
            ['Password', null === $password ? $result['password'] : '[provided]'],
            ['Seeders', $shouldSeed ? 'Run' : 'Skipped'],
        ]);

        return self::SUCCESS;
    }

    /**
     * Resolve the owning account from the --account or --plan options.
     *
     * Returns null when neither option is given (legacy account-less path),
     * or false when an option references something that cannot be resolved.
     */
    private function resolveAccount(string $tenantName): Account|false|null
    {
        $accountOption = $this->option('account');

        if (null !== $accountOption) {
            $account = Account::query()
                ->where('id', $accountOption)
                ->orWhere('slug', $accountOption)
                ->first();

            if (null === $account) {
                $this->error(sprintf('Account [%s] was not found.', $accountOption));

                return false;
            }

            return $account;
        }

        $planOption = $this->option('plan');

        if (null !== $planOption) {
            $plan = Plan::query()
                ->where('id', $planOption)
                ->orWhere('slug', $planOption)
                ->first();

            if (null === $plan) {
                $this->error(sprintf('Plan [%s] was not found.', $planOption));

                return false;
            }

            return $this->createAccountAction->execute($tenantName, $plan)['account'];
        }

        return null;
    }

    private function shouldSkipExistingTenant(): bool
    {
        if ( ! (bool) $this->option('if-missing')) {
            return false;
        }

        $domain = (string) $this->argument('domain');

        if ( ! TenantDomain::query()->where('name', $domain)->exists()) {
            return false;
        }

        $this->info(sprintf('Tenant domain [%s] already exists; provisioning skipped.', $domain));

        return true;
    }

    /**
     * Validate the optional password option: null when omitted, false when invalid.
     */
    private function validatedPassword(): string|false|null
    {
        $password = $this->option('password');

        if (null === $password) {
            return null;
        }

        $validator = Validator::make(
            ['password' => $password],
            ['password' => ['required', 'string', 'min:8', 'max:255']],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return false;
        }

        return (string) $password;
    }

    /**
     * @return array{
     *     name: string,
     *     domain: string,
     *     username: string,
     *     email: string
     * }|null
     */
    private function validatedInput(): ?array
    {
        $input = [
            'name'     => $this->argument('name'),
            'domain'   => $this->argument('domain'),
            'username' => $this->argument('username'),
            'email'    => $this->argument('email'),
        ];

        $validator = Validator::make($input, [
            'name'   => ['required', 'string', 'max:255'],
            'domain' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tenant_domains', 'name')->withoutTrashed(),
            ],
            'username' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->withoutTrashed()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return null;
        }

        /** @var array{name: string, domain: string, username: string, email: string} $data */
        $data = $validator->validated();

        return $data;
    }

    private function shouldSeedTenant(): bool
    {
        if ((bool) $this->option('seed')) {
            return true;
        }

        if ( ! $this->input->isInteractive()) {
            return false;
        }

        return $this->confirm('Run default tenant seeders?', true);
    }
}
