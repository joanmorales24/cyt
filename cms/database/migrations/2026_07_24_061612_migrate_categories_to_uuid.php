<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('categories', 'id') || !$this->isUuid('categories', 'id')) {
            $driver = DB::connection()->getDriverName();

            if ($driver === 'sqlite') {
                $this->upSqlite();
            } else {
                $this->upMysql();
            }
        }
    }

    private function upMysql(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::create('categories_new', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        DB::table('categories')->get()->each(function ($cat) {
            DB::table('categories_new')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => $cat->name,
                'slug' => $cat->slug,
                'created_at' => $cat->created_at,
                'updated_at' => $cat->updated_at,
            ]);
        });

        Schema::drop('categories');
        Schema::rename('categories_new', 'categories');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function upSqlite(): void
    {
        if (Schema::hasTable('categories_new')) {
            Schema::drop('categories_new');
        }

        Schema::create('categories_new', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        DB::table('categories')->get()->each(function ($cat) {
            DB::table('categories_new')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => $cat->name,
                'slug' => $cat->slug,
                'created_at' => $cat->created_at,
                'updated_at' => $cat->updated_at,
            ]);
        });

        Schema::drop('categories');
        Schema::rename('categories_new', 'categories');
    }

    private function isUuid($table, $column): bool
    {
        $columns = Schema::getColumns($table);
        foreach ($columns as $col) {
            if ($col['name'] === $column) {
                return str_contains($col['type'], 'uuid') || str_contains($col['type'], 'varchar');
            }
        }
        return false;
    }

    public function down(): void
    {
    }
};
