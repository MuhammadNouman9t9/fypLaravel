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
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table): void {
                // Index for brand filtering
                $table->index('brand');

                // Index for price filtering
                $table->index('price');

                // Composite index for active products with price
                $table->index(['is_active', 'price']);
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table): void {
                // Index for user orders (if not already exists from foreign key)
                if (! $this->hasIndex('orders', 'orders_user_id_index')) {
                    $table->index('user_id', 'orders_user_id_index');
                }

                // Index for payment status
                $table->index('payment_status');

                // Composite index for user orders with payment status
                $table->index(['user_id', 'payment_status', 'created_at']);
            });
        }

        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table): void {
                // Index for parent_id (if not already exists from foreign key)
                if (! $this->hasIndex('categories', 'categories_parent_id_index')) {
                    $table->index('parent_id', 'categories_parent_id_index');
                }
            });
        }

        // order_items.product_id already has index from foreign key constraint
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->dropIndex(['brand']);
                $table->dropIndex(['price']);
                $table->dropIndex(['is_active', 'price']);
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table): void {
                if ($this->hasIndex('orders', 'orders_user_id_index')) {
                    $table->dropIndex('orders_user_id_index');
                }
                $table->dropIndex(['payment_status']);
                $table->dropIndex(['user_id', 'payment_status', 'created_at']);
            });
        }

        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table): void {
                if ($this->hasIndex('categories', 'categories_parent_id_index')) {
                    $table->dropIndex('categories_parent_id_index');
                }
            });
        }
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
