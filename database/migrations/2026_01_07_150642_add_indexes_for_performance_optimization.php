<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add index on user_id for faster role lookups
        Schema::table('role_user', function (Blueprint $table): void {
            if (! $this->hasIndex('role_user', 'role_user_user_id_index')) {
                $table->index('user_id', 'role_user_user_id_index');
            }
        });

        // Add index on roles.name for faster role name lookups
        Schema::table('roles', function (Blueprint $table): void {
            if (! $this->hasIndex('roles', 'roles_name_index')) {
                $table->index('name', 'roles_name_index');
            }
        });

        // Add index on users.email for faster login lookups (if not already exists)
        Schema::table('users', function (Blueprint $table): void {
            if (! $this->hasIndex('users', 'users_email_index')) {
                $table->index('email', 'users_email_index');
            }
        });

        // Add index on users.phone for faster phone lookups
        Schema::table('users', function (Blueprint $table): void {
            if (! $this->hasIndex('users', 'users_phone_index')) {
                $table->index('phone', 'users_phone_index');
            }
        });

        // Add index on addresses.user_id for faster address lookups
        Schema::table('addresses', function (Blueprint $table): void {
            if (! $this->hasIndex('addresses', 'addresses_user_id_index')) {
                $table->index('user_id', 'addresses_user_id_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_user', function (Blueprint $table): void {
            if ($this->hasIndex('role_user', 'role_user_user_id_index')) {
                $table->dropIndex('role_user_user_id_index');
            }
        });

        Schema::table('roles', function (Blueprint $table): void {
            if ($this->hasIndex('roles', 'roles_name_index')) {
                $table->dropIndex('roles_name_index');
            }
        });

        Schema::table('users', function (Blueprint $table): void {
            if ($this->hasIndex('users', 'users_email_index')) {
                $table->dropIndex('users_email_index');
            }
            if ($this->hasIndex('users', 'users_phone_index')) {
                $table->dropIndex('users_phone_index');
            }
        });

        Schema::table('addresses', function (Blueprint $table): void {
            if ($this->hasIndex('addresses', 'addresses_user_id_index')) {
                $table->dropIndex('addresses_user_id_index');
            }
        });
    }

    /**
     * Check if index exists
     */
    private function hasIndex(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();

        try {
            $result = $connection->select(
                'SELECT COUNT(*) as count FROM information_schema.statistics 
                 WHERE table_schema = ? AND table_name = ? AND index_name = ?',
                [$database, $table, $index]
            );

            return $result[0]->count > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
};
