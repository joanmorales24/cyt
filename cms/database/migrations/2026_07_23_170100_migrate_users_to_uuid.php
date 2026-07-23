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
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        DB::table('users')->get()->each(function ($user) {
            DB::table('users')->where('id', $user->id)->update(['uuid' => \Illuminate\Support\Str::uuid()]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
            $table->renameColumn('uuid', 'id');
            $table->primary('id');
        });
    }

    private function upSqlite(): void
    {
        Schema::create('users_new', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_super_admin')->default(false);
            $table->timestamps();
        });

        $users = DB::table('users')->get();
        foreach ($users as $user) {
            DB::table('users_new')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'remember_token' => $user->remember_token,
                'is_admin' => $user->is_admin,
                'is_super_admin' => $user->is_super_admin,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        Schema::drop('users');
        Schema::rename('users_new', 'users');
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
        $maxId = DB::table('users')->max('id');

        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->id()->first();
        });

        DB::statement('ALTER TABLE users AUTO_INCREMENT = ' . ($maxId + 1));
    }

    private function downSqlite(): void
    {
        Schema::create('users_old', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_super_admin')->default(false);
            $table->timestamps();
        });

        $users = DB::table('users')->get();
        foreach ($users as $user) {
            DB::table('users_old')->insert([
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'remember_token' => $user->remember_token,
                'is_admin' => $user->is_admin,
                'is_super_admin' => $user->is_super_admin,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        Schema::drop('users');
        Schema::rename('users_old', 'users');
    }
};
