<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table to remove CHECK constraints
        if (DB::getDriverName() === 'sqlite') {
            // Create temporary table without constraint
            DB::statement('
                CREATE TABLE banners_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title VARCHAR(255) NOT NULL,
                    subtitle VARCHAR(255) NULL,
                    image_url VARCHAR(255) NULL,
                    link_url VARCHAR(255) NULL,
                    position VARCHAR(50) NOT NULL,
                    order_index INTEGER DEFAULT 0,
                    is_active BOOLEAN DEFAULT 1,
                    start_date DATETIME NULL,
                    end_date DATETIME NULL,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL
                )
            ');
            
            // Copy data from old table to new table
            DB::statement('
                INSERT INTO banners_new 
                SELECT * FROM banners
            ');
            
            // Drop old table
            DB::statement('DROP TABLE banners');
            
            // Rename new table
            DB::statement('ALTER TABLE banners_new RENAME TO banners');
        } else {
            // For other databases, just modify the column
            Schema::table('banners', function (Blueprint $table) {
                $table->string('position', 50)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration removes constraints, so down() would need to add them back
        // But since we don't know the original constraint, we'll leave it empty
        // You can manually add constraints back if needed
    }
};
