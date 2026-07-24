<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        if (!Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->string('slug')->unique();
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

        Schema::create('tags_new', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        DB::table('tags')->get()->each(function ($tag) {
            DB::table('tags_new')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => $tag->name,
                'slug' => $tag->slug,
                'created_at' => $tag->created_at,
                'updated_at' => $tag->updated_at,
            ]);
        });

        Schema::drop('tags');
        Schema::rename('tags_new', 'tags');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function upSqlite(): void
    {
        if (Schema::hasTable('tags_new')) {
            Schema::drop('tags_new');
        }

        Schema::create('tags_new', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        DB::table('tags')->get()->each(function ($tag) {
            DB::table('tags_new')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => $tag->name,
                'slug' => $tag->slug,
                'created_at' => $tag->created_at,
                'updated_at' => $tag->updated_at,
            ]);
        });

        Schema::drop('tags');
        Schema::rename('tags_new', 'tags');
    }

    public function down(): void
    {
    }
};

