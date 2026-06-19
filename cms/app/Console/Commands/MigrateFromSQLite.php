<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateFromSQLite extends Command {
    protected $signature = 'migrate:sqlite';
    protected $description = 'Migrate data from SQLite';

    public function handle() {
        $this->info('Ready to import data');
    }
}
