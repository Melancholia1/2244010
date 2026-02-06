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
        // Check if table exists
        if (!Schema::hasTable('social_media')) {
            // If table doesn't exist, create it with correct structure
            Schema::create('social_media', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('link');
                $table->string('icon')->nullable();
                $table->integer('order_index')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
            return;
        }

        // For SQLite, we need to recreate the table to modify structure
        if (DB::getDriverName() === 'sqlite') {
            // Get column info
            $columns = DB::select("PRAGMA table_info(social_media)");
            $columnNames = array_map(fn($col) => $col->name, $columns);
            $hasLinkUrl = in_array('link_url', $columnNames);
            $hasLink = in_array('link', $columnNames);
            $hasOrderIndex = in_array('order_index', $columnNames);
            $hasIsActive = in_array('is_active', $columnNames);

            // Create temporary table with correct structure
            DB::statement('
                CREATE TABLE social_media_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name VARCHAR(255) NOT NULL,
                    link VARCHAR(255) NOT NULL,
                    icon VARCHAR(255) NULL,
                    order_index INTEGER DEFAULT 0,
                    is_active BOOLEAN DEFAULT 1,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL
                )
            ');

            // Build SELECT statement based on what columns exist
            $selectFields = ['id', 'name'];
            
            // Determine link field
            if ($hasLinkUrl) {
                $selectFields[] = 'link_url as link';
            } elseif ($hasLink) {
                $selectFields[] = 'link';
            } else {
                $selectFields[] = "'' as link";
            }
            
            // Add other fields
            $selectFields[] = $hasOrderIndex ? 'COALESCE(order_index, 0) as order_index' : '0 as order_index';
            $selectFields[] = $hasIsActive ? 'COALESCE(is_active, 1) as is_active' : '1 as is_active';
            if (in_array('icon', $columnNames)) {
                $selectFields[] = 'icon';
            } else {
                $selectFields[] = 'NULL as icon';
            }
            $selectFields[] = 'created_at';
            $selectFields[] = 'updated_at';

            $selectSQL = 'SELECT ' . implode(', ', $selectFields) . ' FROM social_media';
            
            // Copy data
            DB::statement("INSERT INTO social_media_new (id, name, link, icon, order_index, is_active, created_at, updated_at) $selectSQL");

            // Drop old table
            DB::statement('DROP TABLE social_media');

            // Rename new table
            DB::statement('ALTER TABLE social_media_new RENAME TO social_media');
        } else {
            // For other databases (MySQL, PostgreSQL), use ALTER TABLE
            if (Schema::hasColumn('social_media', 'link_url')) {
                Schema::table('social_media', function (Blueprint $table) {
                    $table->renameColumn('link_url', 'link');
                });
            } elseif (!Schema::hasColumn('social_media', 'link')) {
                Schema::table('social_media', function (Blueprint $table) {
                    $table->string('link')->after('name');
                });
            }

            if (!Schema::hasColumn('social_media', 'order_index')) {
                Schema::table('social_media', function (Blueprint $table) {
                    $table->integer('order_index')->default(0)->after('icon');
                });
            }

            if (!Schema::hasColumn('social_media', 'is_active')) {
                Schema::table('social_media', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true)->after('order_index');
                });
            }

            // Make icon nullable if it isn't already
            Schema::table('social_media', function (Blueprint $table) {
                $table->string('icon')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // If needed, reverse the changes
        // For safety, we'll leave this empty as rollback might cause data loss
    }
};

