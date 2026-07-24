<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportData extends Command
{
    protected $signature = 'import:data';
    protected $description = 'Import data from JSON seed file';

    public function handle()
    {
        $file = database_path('seeds/data.json');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $json = file_get_contents($file);
        $data = json_decode($json, true);

        if (!$data) {
            $this->error('Invalid JSON');
            return 1;
        }

        try {
            $driver = DB::connection()->getDriverName();
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
            }
            DB::beginTransaction();

            // Import posts
            if (isset($data['posts']) && is_array($data['posts'])) {
                DB::table('posts')->truncate();
                $count = 0;
                foreach ($data['posts'] as $post) {
                    try {
                        DB::table('posts')->insert($post);
                        $count++;
                    } catch (\Exception $e) {
                        $this->warn("Failed to import post {$post['id']}: " . $e->getMessage());
                    }
                }
                $this->info("✓ Imported {$count} posts");
            }

            // Import categories
            if (isset($data['categories']) && is_array($data['categories'])) {
                DB::table('categories')->truncate();
                $count = 0;
                foreach ($data['categories'] as $cat) {
                    try {
                        DB::table('categories')->insert($cat);
                        $count++;
                    } catch (\Exception $e) {
                        $this->warn("Failed to import category: " . $e->getMessage());
                    }
                }
                $this->info("✓ Imported {$count} categories");
            }

            // Import tags
            if (isset($data['tags']) && is_array($data['tags'])) {
                DB::table('tags')->truncate();
                $count = 0;
                foreach ($data['tags'] as $tag) {
                    try {
                        DB::table('tags')->insert($tag);
                        $count++;
                    } catch (\Exception $e) {
                        $this->warn("Failed to import tag: " . $e->getMessage());
                    }
                }
                $this->info("✓ Imported {$count} tags");
            }

            // Import post_category
            if (isset($data['post_category']) && is_array($data['post_category'])) {
                DB::table('post_category')->truncate();
                $count = 0;
                foreach ($data['post_category'] as $relation) {
                    if (is_array($relation)) {
                        try {
                            DB::table('post_category')->insert($relation);
                            $count++;
                        } catch (\Exception $e) {
                            $this->warn("Failed to import post_category: " . $e->getMessage());
                        }
                    }
                }
                $this->info("✓ Imported {$count} post-category relations");
            }

            // Import post_tag
            if (isset($data['post_tag']) && is_array($data['post_tag'])) {
                DB::table('post_tag')->truncate();
                $count = 0;
                foreach ($data['post_tag'] as $relation) {
                    if (is_array($relation)) {
                        try {
                            DB::table('post_tag')->insert($relation);
                            $count++;
                        } catch (\Exception $e) {
                            $this->warn("Failed to import post_tag: " . $e->getMessage());
                        }
                    }
                }
                $this->info("✓ Imported {$count} post-tag relations");
            }

            DB::commit();
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }
            $this->info('Data import completed successfully!');
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }
    }
}
