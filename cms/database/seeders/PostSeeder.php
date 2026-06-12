<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder {
    public function run() {
        $dataPath = database_path('seeders/data.json');

        if (!file_exists($dataPath)) {
            $this->command->error('data.json not found');
            return;
        }

        $data = json_decode(file_get_contents($dataPath), true);

        foreach($data['categories'] as $cat) {
            DB::table('categories')->insertOrIgnore($cat);
        }

        foreach($data['posts'] as $post) {
            DB::table('posts')->insertOrIgnore($post);
        }

        foreach($data['tags'] as $tag) {
            DB::table('tags')->insertOrIgnore($tag);
        }

        foreach($data['post_category'] as $pc) {
            DB::table('post_category')->insertOrIgnore($pc);
        }

        foreach($data['post_tag'] as $pt) {
            DB::table('post_tag')->insertOrIgnore($pt);
        }

        $this->command->info('Posts migrados: ' . count($data['posts']));
    }
}
