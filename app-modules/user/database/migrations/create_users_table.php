<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        $this->createUserProfilePhonesTable();
        $this->createUserProfileDocumentsTable();
        $this->createUserProfileBalancesTable();
        $this->createUserProfilesTable();
        $this->createUsersTable();
        $this->createSocialiteUsersTable();
        $this->createPasswordResetTokensTable();
        $this->createSessionsTable();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_profile_phones');
        Schema::dropIfExists('user_profile_documents');
        Schema::dropIfExists('user_profile_balances');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('socialite_users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    private function createUserProfileBalancesTable(): void
    {
        Schema::create('user_profile_balances', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_profile_id');
            $table->unsignedBigInteger('currency_id');
            $table->bigInteger('amount')
                ->index();
            $table->boolean('status')
                ->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * @return void
     */
    private function createUserProfileDocumentsTable(): void
    {
        Schema::create('user_profile_documents', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_profile_id');
            $table->string('status')
                ->index();
            $table->timestampTz('verified_at')
                ->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * @return void
     */
    private function createUserProfilePhonesTable(): void
    {
        Schema::create('user_profile_phones', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_profile_id');
            $table->char('country')
                ->index();
            $table->string('phone')
                ->index();
            $table->string('phone_normalized')
                ->index();
            $table->string('phone_national')
                ->index();
            $table->string('phone_e164')
                ->index();
            $table->string('status')
                ->index();
            $table->timestampTz('verified_at')
                ->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * @return void
     */
    private function createUserProfilesTable(): void
    {
        Schema::create('user_profiles', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->string('first_name')
                ->nullable()
                ->index();
            $table->string('last_name')
                ->nullable()
                ->index();
            $table->text('description')
                ->nullable();
            $table->timestampTz('birthdate')
                ->nullable()
                ->index();
            $table->boolean('status')
                ->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * @return void
     */
    private function createUsersTable(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('username');
            $table->string('email');
            $table->timestampTz('email_verified_at')
                ->nullable();
            $table->string('password');
            $table->string('password_fingerprint', 64)
                ->nullable();
            $table->rememberToken();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['tenant_id']);
            $table->index(['tenant_id', 'username']);
            $table->index(['tenant_id', 'email']);
            $table->index(['tenant_id', 'password_fingerprint']);
        });
    }

    /**
     * @return void
     */
    private function createSocialiteUsersTable(): void
    {
        Schema::create('socialite_users', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->string('provider');
            $table->string('provider_id');
            $table->timestampsTz();

            $table->unique([
                'provider',
                'provider_id',
            ]);
        });
    }

    /**
     * @return void
     */
    private function createPasswordResetTokensTable(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->string('email')
                ->primary();
            $table->string('token');
            $table->timestampTz('created_at')
                ->nullable();
        });
    }

    /**
     * @return void
     */
    private function createSessionsTable(): void
    {
        Schema::create('sessions', function (Blueprint $table): void {
            $table->string('id')
                ->primary();
            $table->foreignId('user_id')
                ->nullable()
                ->index();
            $table->string('ip_address', 45)
                ->nullable();
            $table->text('user_agent')
                ->nullable();
            $table->longText('payload');
            $table->integer('last_activity')
                ->index();
        });
    }
};
