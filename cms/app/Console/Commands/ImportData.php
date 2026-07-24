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

        try {
            DB::beginTransaction();

            // Import posts
            if (isset($data['posts'])) {
                foreach ($data['posts'] as $post) {
                    DB::table('posts')->updateOrCreate(
                        ['id' => $post['id']],
                        $post
                    );
                }
                $this->info('✓ Imported ' . count($data['posts']) . ' posts');
            }

            // Import categories
            if (isset($data['categories'])) {
                foreach ($data['categories'] as $category) {
                    DB::table('categories')->updateOrCreate(
                        ['id' => $category['id']],
                        $category
                    );
                }
                $this->info('✓ Imported ' . count($data['categories']) . ' categories');
            }

            // Import tags
            if (isset($data['tags'])) {
                foreach ($data['tags'] as $tag) {
                    DB::table('tags')->updateOrCreate(
                        ['id' => $tag['id']],
                        $tag
                    );
                }
                $this->info('✓ Imported ' . count($data['tags']) . ' tags');
            }

            // Import post_category
            if (isset($data['post_category'])) {
                DB::table('post_category')->truncate();
                foreach ($data['post_category'] as $relation) {
                    DB::table('post_category')->insert($relation);
                }
                $this->info('✓ Imported ' . count($data['post_category']) . ' post-category relations');
            }

            // Import post_tag
            if (isset($data['post_tag'])) {
                DB::table('post_tag')->truncate();
                foreach ($data['post_tag'] as $relation) {
                    DB::table('post_tag')->insert($relation);
                }
                $this->info('✓ Imported ' . count($data['post_tag']) . ' post-tag relations');
            }

            DB::commit();
            $this->info('Data import completed successfully!');
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }
    }
}
