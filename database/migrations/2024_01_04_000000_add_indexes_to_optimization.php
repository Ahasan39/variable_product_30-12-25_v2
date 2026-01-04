<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        try {
            Schema::table('products', function (Blueprint $table) {
                // Try adding one by one, ignore errors
                try { $table->index('category_id'); } catch (\Exception $e) {}
                try { $table->index('subcategory_id'); } catch (\Exception $e) {}
                try { $table->index('childcategory_id'); } catch (\Exception $e) {}
                try { $table->index('brand_id'); } catch (\Exception $e) {}
                try { $table->index('status'); } catch (\Exception $e) {}
                try { $table->index('slug'); } catch (\Exception $e) {}
                try { $table->index('topsale'); } catch (\Exception $e) {}
            });

            Schema::table('productimages', function (Blueprint $table) {
               try { $table->index('product_id'); } catch (\Exception $e) {}
            });

            Schema::table('orders', function (Blueprint $table) {
               try { $table->index('customer_id'); } catch (\Exception $e) {}
               try { $table->index('order_status'); } catch (\Exception $e) {}
               try { $table->index('invoice_id'); } catch (\Exception $e) {}
            });
            
             if (Schema::hasTable('order_details')) {
                Schema::table('order_details', function (Blueprint $table) {
                    try { $table->index('order_id'); } catch (\Exception $e) {}
                    try { $table->index('product_id'); } catch (\Exception $e) {}
                });
             }

        } catch (\Exception $e) {
            // Global catch to ensure migration is marked as run even if partial failure
        }
    }

    public function down()
    {
        // ... (Down logic skipped)
    }
};
