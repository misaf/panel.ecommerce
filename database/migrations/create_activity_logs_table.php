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
        $this->createActivityLogsTable();
        $this->addEventColumn();
        $this->addBatchUuidColumn();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('activitylog.table_name'));
    }

    /**
     * @return string|null
     */
    public function getConnection(): ?string
    {
        return config('activitylog.database_connection');
    }

    /**
     * @return void
     */
    private function createActivityLogsTable(): void
    {
        Schema::create(config('activitylog.table_name'), function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('log_name')
                ->nullable();
            $table->text('description');
            $table->nullableMorphs('subject', 'subject');
            $table->nullableMorphs('causer', 'causer');
            $table->json('properties')
                ->nullable();
            $table->timestamps();

            $table->index('log_name');
        });
    }

    /**
     * @return void
     */
    private function addEventColumn(): void
    {
        Schema::table(config('activitylog.table_name'), function (Blueprint $table): void {
            $table->string('event')
                ->nullable()
                ->after('subject_type');
        });
    }

    /**
     * @return void
     */
    private function addBatchUuidColumn(): void
    {
        Schema::table(config('activitylog.table_name'), function (Blueprint $table): void {
            $table->uuid('batch_uuid')
                ->nullable()
                ->after('properties');
        });
    }
};
