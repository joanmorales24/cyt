<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        DB::table('leads')->get()->each(function ($lead) {
            DB::table('leads')->where('id', $lead->id)->update(['uuid' => \Illuminate\Support\Str::uuid()]);
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
            $table->renameColumn('uuid', 'id');
            $table->primary('id');
        });
    }

    public function down(): void
    {
        $maxId = DB::table('leads')->max('id');

        Schema::table('leads', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->id()->first();
        });

        DB::statement('ALTER TABLE leads AUTO_INCREMENT = ' . ($maxId + 1));
    }
};
