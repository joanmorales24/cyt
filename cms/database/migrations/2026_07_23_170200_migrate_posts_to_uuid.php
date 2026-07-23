<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        DB::table('posts')->get()->each(function ($post) {
            DB::table('posts')->where('id', $post->id)->update(['uuid' => \Illuminate\Support\Str::uuid()]);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
            $table->renameColumn('uuid', 'id');
            $table->primary('id');
        });
    }

    public function down(): void
    {
        $maxId = DB::table('posts')->get()->count();

        Schema::table('posts', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->id()->first();
        });

        DB::statement('ALTER TABLE posts AUTO_INCREMENT = ' . ($maxId + 1));
    }
};
