<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('posts', 'user_id')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->uuid('user_id')->nullable()->after('id');
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            });

            $firstUserId = DB::table('users')->first()?->id;
            if ($firstUserId) {
                DB::table('posts')->update(['user_id' => $firstUserId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
