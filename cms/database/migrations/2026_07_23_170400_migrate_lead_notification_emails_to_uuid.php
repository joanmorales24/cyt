<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead_notification_emails', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        DB::table('lead_notification_emails')->get()->each(function ($email) {
            DB::table('lead_notification_emails')->where('id', $email->id)->update(['uuid' => \Illuminate\Support\Str::uuid()]);
        });

        Schema::table('lead_notification_emails', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
            $table->renameColumn('uuid', 'id');
            $table->primary('id');
        });
    }

    public function down(): void
    {
        $maxId = DB::table('lead_notification_emails')->max('id');

        Schema::table('lead_notification_emails', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
        });

        Schema::table('lead_notification_emails', function (Blueprint $table) {
            $table->id()->first();
        });

        DB::statement('ALTER TABLE lead_notification_emails AUTO_INCREMENT = ' . ($maxId + 1));
    }
};
