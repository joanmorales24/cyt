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

        Schema::create('leads_new', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->text('message')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });

        DB::table('leads')->get()->each(function ($lead) {
            DB::table('leads_new')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'company' => $lead->company,
                'message' => $lead->message,
                'source' => $lead->source,
                'created_at' => $lead->created_at,
                'updated_at' => $lead->updated_at,
            ]);
        });

        Schema::drop('leads');
        Schema::rename('leads_new', 'leads');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function upSqlite(): void
    {
        Schema::create('leads_new', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->text('message')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });

        DB::table('leads')->get()->each(function ($lead) {
            DB::table('leads_new')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'company' => $lead->company,
                'message' => $lead->message,
                'source' => $lead->source,
                'created_at' => $lead->created_at,
                'updated_at' => $lead->updated_at,
            ]);
        });

        Schema::drop('leads');
        Schema::rename('leads_new', 'leads');
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

    private function downSqlite(): void
    {
        Schema::create('leads_old', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->text('message')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });

        DB::table('leads')->get()->each(function ($lead) {
            DB::table('leads_old')->insert([
                'name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'company' => $lead->company,
                'message' => $lead->message,
                'source' => $lead->source,
                'created_at' => $lead->created_at,
                'updated_at' => $lead->updated_at,
            ]);
        });

        Schema::drop('leads');
        Schema::rename('leads_old', 'leads');
    }
};
