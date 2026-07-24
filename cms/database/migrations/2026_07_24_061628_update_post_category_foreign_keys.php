<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $this->upSqlite();
        } else {
            $this->upMysql();
        }
    }

    private function upMysql(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::statement('ALTER TABLE post_category DROP FOREIGN KEY post_category_category_id_foreign');
        DB::statement('ALTER TABLE post_category MODIFY category_id CHAR(36) NOT NULL');
        DB::statement('ALTER TABLE post_category ADD CONSTRAINT post_category_category_id_foreign FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE');

        DB::statement('ALTER TABLE post_tag DROP FOREIGN KEY post_tag_tag_id_foreign');
        DB::statement('ALTER TABLE post_tag MODIFY tag_id CHAR(36) NOT NULL');
        DB::statement('ALTER TABLE post_tag ADD CONSTRAINT post_tag_tag_id_foreign FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function upSqlite(): void
    {
        if (Schema::hasTable('post_category_new')) {
            Schema::drop('post_category_new');
        }

        Schema::create('post_category_new', function (Blueprint $table) {
            $table->uuid('post_id');
            $table->uuid('category_id');
            $table->primary(['post_id', 'category_id']);
            $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });

        DB::table('post_category')->get()->each(function ($rel) {
            DB::table('post_category_new')->insert([
                'post_id' => $rel->post_id,
                'category_id' => $rel->category_id,
            ]);
        });

        Schema::drop('post_category');
        Schema::rename('post_category_new', 'post_category');

        if (Schema::hasTable('post_tag_new')) {
            Schema::drop('post_tag_new');
        }

        Schema::create('post_tag_new', function (Blueprint $table) {
            $table->uuid('post_id');
            $table->uuid('tag_id');
            $table->primary(['post_id', 'tag_id']);
            $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete();
            $table->foreign('tag_id')->references('id')->on('tags')->cascadeOnDelete();
        });

        DB::table('post_tag')->get()->each(function ($rel) {
            DB::table('post_tag_new')->insert([
                'post_id' => $rel->post_id,
                'tag_id' => $rel->tag_id,
            ]);
        });

        Schema::drop('post_tag');
        Schema::rename('post_tag_new', 'post_tag');
    }

    public function down(): void
    {
    }
};
