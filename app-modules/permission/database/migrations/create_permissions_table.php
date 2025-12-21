<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    private array $columnNames;

    private string $pivotPermission;

    private string $pivotRole;

    private array $tableNames;

    private bool $teams;

    public function __construct()
    {
        $this->columnNames = Config::array('permission.column_names');
        $this->tableNames = Config::array('permission.table_names');
        $this->teams = Config::boolean('permission.teams');
        $this->pivotRole = $this->columnNames['role_pivot_key'] ?? 'role_id';
        $this->pivotPermission = $this->columnNames['permission_pivot_key'] ?? 'permission_id';
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists($this->tableNames['role_has_permissions']);
        Schema::dropIfExists($this->tableNames['model_has_roles']);
        Schema::dropIfExists($this->tableNames['model_has_permissions']);
        Schema::dropIfExists($this->tableNames['roles']);
        Schema::dropIfExists($this->tableNames['permissions']);
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    public function up(): void
    {
        $this->ensureExistsConfigurations();
        Schema::disableForeignKeyConstraints();
        $this->createPermissionsTable();
        $this->createRolesTable();
        $this->createModelHasPermissionsTable();
        $this->createModelHasRolesTable();
        $this->createRoleHasPermissionsTable();
        Schema::enableForeignKeyConstraints();
        $this->clearCache();
    }

    private function clearCache(): void
    {
        app('cache')
            ->store('default' !== Config::string('permission.cache.store') ? Config::string('permission.cache.store') : null)
            ->forget(Config::string('permission.cache.key'));
    }

    private function createModelHasPermissionsTable(): void
    {
        Schema::create($this->tableNames['model_has_permissions'], function (Blueprint $table): void {
            $table->unsignedBigInteger($this->pivotPermission);

            $table->string('model_type');
            $table->unsignedBigInteger($this->columnNames['model_morph_key']);
            $table->index([$this->columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign($this->pivotPermission)
                ->references('id') // permission id
                ->on($this->tableNames['permissions'])
                ->onDelete('cascade');

            if ($this->teams) {
                $table->unsignedBigInteger($this->columnNames['team_foreign_key']);
                $table->index($this->columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');

                $table->primary(
                    [$this->columnNames['team_foreign_key'], $this->pivotPermission, $this->columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary',
                );
            } else {
                $table->primary(
                    [$this->pivotPermission, $this->columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary',
                );
            }

        });
    }

    private function createModelHasRolesTable(): void
    {
        Schema::create($this->tableNames['model_has_roles'], function (Blueprint $table): void {
            $table->unsignedBigInteger($this->pivotRole);

            $table->string('model_type');
            $table->unsignedBigInteger($this->columnNames['model_morph_key']);
            $table->index([$this->columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign($this->pivotRole)
                ->references('id') // role id
                ->on($this->tableNames['roles'])
                ->onDelete('cascade');

            if ($this->teams) {
                $table->unsignedBigInteger($this->columnNames['team_foreign_key']);
                $table->index($this->columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');

                $table->primary(
                    [$this->columnNames['team_foreign_key'], $this->pivotRole, $this->columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary',
                );
            } else {
                $table->primary(
                    [$this->pivotRole, $this->columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary',
                );
            }
        });
    }

    private function createPermissionsTable(): void
    {
        Schema::create($this->tableNames['permissions'], function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    private function createRoleHasPermissionsTable(): void
    {
        Schema::create($this->tableNames['role_has_permissions'], function (Blueprint $table): void {
            $table->unsignedBigInteger($this->pivotPermission);
            $table->unsignedBigInteger($this->pivotRole);

            $table->foreign($this->pivotPermission)
                ->references('id') // permission id
                ->on($this->tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign($this->pivotRole)
                ->references('id') // role id
                ->on($this->tableNames['roles'])
                ->onDelete('cascade');

            $table->primary([$this->pivotPermission, $this->pivotRole], 'role_has_permissions_permission_id_role_id_primary');
        });
    }

    private function createRolesTable(): void
    {
        Schema::create($this->tableNames['roles'], function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');

            // Add team foreign key if necessary
            if ($this->teams || Config::boolean('permission.testing', false)) {
                $table->unsignedBigInteger($this->columnNames['team_foreign_key'])->nullable();
                $table->index($this->columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
            }

            $table->string('name');
            $table->string('guard_name');
            $table->timestampsTz();
            $table->softDeletesTz();

            // Add unique constraint if team is enabled
            if ($this->teams || Config::boolean('permission.testing', false)) {
                $table->unique([$this->columnNames['team_foreign_key'], 'name', 'guard_name']);
            }
        });
    }

    private function ensureExistsConfigurations(): void
    {
        if (empty($this->tableNames)) {
            throw new Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }
    }
};
