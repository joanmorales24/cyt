<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            return;
        }

        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $this->upSqlite();
        } else {
            $this->upMysql();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function upMysql(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::create('categories_new', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('categories')->get()->each(function ($cat) {
            DB::table('categories_new')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => $cat->name,
                'slug' => $cat->slug,
                'description' => $cat->description ?? null,
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
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('categories')->get()->each(function ($cat) {
            DB::table('categories_new')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => $cat->name,
                'slug' => $cat->slug,
                'description' => $cat->description ?? null,
                'created_at' => $cat->created_at,
                'updated_at' => $cat->updated_at,
            ]);
        });

        Schema::drop('categories');
        Schema::rename('categories_new', 'categories');
    }

    public function down(): void
    {
    }
};
