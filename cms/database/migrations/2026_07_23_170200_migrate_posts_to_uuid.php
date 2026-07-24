<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
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

        Schema::create('posts_new', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->integer('wp_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('featured_image_alt')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_focus_keyword')->nullable();
            $table->string('seo_canonical_url')->nullable();
            $table->string('og_image')->nullable();
            $table->string('old_slug')->nullable();
            $table->timestamps();
        });

        $firstUserId = DB::table('users')->first()?->id;

        if (Schema::hasTable('posts')) {
            DB::table('posts')->get()->each(function ($post) use ($firstUserId) {
            DB::table('posts_new')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'user_id' => $firstUserId,
                'wp_id' => $post->wp_id,
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'excerpt' => $post->excerpt,
                'featured_image' => $post->featured_image,
                'featured_image_alt' => $post->featured_image_alt,
                'status' => $post->status,
                'published_at' => $post->published_at,
                'seo_title' => $post->seo_title,
                'seo_description' => $post->seo_description,
                'seo_focus_keyword' => $post->seo_focus_keyword,
                'seo_canonical_url' => $post->seo_canonical_url,
                'og_image' => $post->og_image,
                'old_slug' => $post->old_slug,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
            ]);
            });
        }

        if (Schema::hasTable('posts')) {
            Schema::drop('posts');
        }

        Schema::rename('posts_new', 'posts');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function upSqlite(): void
    {
        $columns = ['id', 'wp_id', 'title', 'slug', 'content', 'excerpt', 'featured_image', 'featured_image_alt', 'status', 'published_at', 'seo_title', 'seo_description', 'seo_focus_keyword', 'seo_canonical_url', 'og_image', 'old_slug', 'created_at', 'updated_at'];

        Schema::create('posts_new', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('wp_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('featured_image_alt')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_focus_keyword')->nullable();
            $table->string('seo_canonical_url')->nullable();
            $table->string('og_image')->nullable();
            $table->string('old_slug')->nullable();
            $table->timestamps();
        });

        DB::table('posts')->get()->each(function ($post) {
            DB::table('posts_new')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'wp_id' => $post->wp_id,
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'excerpt' => $post->excerpt,
                'featured_image' => $post->featured_image,
                'featured_image_alt' => $post->featured_image_alt,
                'status' => $post->status,
                'published_at' => $post->published_at,
                'seo_title' => $post->seo_title,
                'seo_description' => $post->seo_description,
                'seo_focus_keyword' => $post->seo_focus_keyword,
                'seo_canonical_url' => $post->seo_canonical_url,
                'og_image' => $post->og_image,
                'old_slug' => $post->old_slug,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
            ]);
        });

        Schema::drop('posts');
        Schema::rename('posts_new', 'posts');
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $this->downSqlite();
        } else {
            $this->downMysql();
        }
    }

    private function downMysql(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::create('posts_old', function (Blueprint $table) {
            $table->id();
            $table->integer('wp_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('featured_image_alt')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_focus_keyword')->nullable();
            $table->string('seo_canonical_url')->nullable();
            $table->string('og_image')->nullable();
            $table->string('old_slug')->nullable();
            $table->timestamps();
        });

        DB::table('posts')->get()->each(function ($post) {
            DB::table('posts_old')->insert([
                'wp_id' => $post->wp_id,
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'excerpt' => $post->excerpt,
                'featured_image' => $post->featured_image,
                'featured_image_alt' => $post->featured_image_alt,
                'status' => $post->status,
                'published_at' => $post->published_at,
                'seo_title' => $post->seo_title,
                'seo_description' => $post->seo_description,
                'seo_focus_keyword' => $post->seo_focus_keyword,
                'seo_canonical_url' => $post->seo_canonical_url,
                'og_image' => $post->og_image,
                'old_slug' => $post->old_slug,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
            ]);
        });

        Schema::drop('posts');
        Schema::rename('posts_old', 'posts');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function downSqlite(): void
    {
        Schema::create('posts_old', function (Blueprint $table) {
            $table->id();
            $table->integer('wp_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('featured_image_alt')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_focus_keyword')->nullable();
            $table->string('seo_canonical_url')->nullable();
            $table->string('og_image')->nullable();
            $table->string('old_slug')->nullable();
            $table->timestamps();
        });

        DB::table('posts')->get()->each(function ($post) {
            DB::table('posts_old')->insert([
                'wp_id' => $post->wp_id,
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'excerpt' => $post->excerpt,
                'featured_image' => $post->featured_image,
                'featured_image_alt' => $post->featured_image_alt,
                'status' => $post->status,
                'published_at' => $post->published_at,
                'seo_title' => $post->seo_title,
                'seo_description' => $post->seo_description,
                'seo_focus_keyword' => $post->seo_focus_keyword,
                'seo_canonical_url' => $post->seo_canonical_url,
                'og_image' => $post->og_image,
                'old_slug' => $post->old_slug,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
            ]);
        });

        Schema::drop('posts');
        Schema::rename('posts_old', 'posts');
    }
};
