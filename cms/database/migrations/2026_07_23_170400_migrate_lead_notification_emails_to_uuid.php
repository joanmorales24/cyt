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
        Schema::create('lead_notification_emails_new', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        DB::table('lead_notification_emails')->get()->each(function ($email) {
            DB::table('lead_notification_emails_new')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'email' => $email->email,
                'active' => $email->active,
                'created_at' => $email->created_at,
                'updated_at' => $email->updated_at,
            ]);
        });

        Schema::drop('lead_notification_emails');
        Schema::rename('lead_notification_emails_new', 'lead_notification_emails');
    }

    private function upSqlite(): void
    {
        Schema::create('lead_notification_emails_new', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        DB::table('lead_notification_emails')->get()->each(function ($email) {
            DB::table('lead_notification_emails_new')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'email' => $email->email,
                'active' => $email->active,
                'created_at' => $email->created_at,
                'updated_at' => $email->updated_at,
            ]);
        });

        Schema::drop('lead_notification_emails');
        Schema::rename('lead_notification_emails_new', 'lead_notification_emails');
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

    private function downSqlite(): void
    {
        Schema::create('lead_notification_emails_old', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        DB::table('lead_notification_emails')->get()->each(function ($email) {
            DB::table('lead_notification_emails_old')->insert([
                'email' => $email->email,
                'active' => $email->active,
                'created_at' => $email->created_at,
                'updated_at' => $email->updated_at,
            ]);
        });

        Schema::drop('lead_notification_emails');
        Schema::rename('lead_notification_emails_old', 'lead_notification_emails');
    }
};
