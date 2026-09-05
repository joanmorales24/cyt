<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Signature('app:migrate-featured-images-to-media-library')]
#[Description('Migrate legacy featured_image paths to the Media Library')]
class MigrateFeaturedImagesToMediaLibrary extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $posts = Post::whereNotNull('featured_image')->get();

        $migrated = 0;
        $skipped = 0;

        foreach ($posts as $post) {
            if ($post->getFirstMedia('featured_image')) {
                $skipped++;
                continue;
            }

            $path = $post->featured_image;

            if (Str::startsWith($path, 'http')) {
                $this->warn("Post \"{$post->title}\": featured_image is an external URL, skipping.");
                $skipped++;
                continue;
            }

            if (! Storage::disk('public')->exists($path)) {
                $this->warn("Post \"{$post->title}\": file not found at {$path}, skipping.");
                $skipped++;
                continue;
            }

            try {
                $post->addMediaFromDisk($path, 'public')
                    ->toMediaCollection('featured_image');

                $migrated++;
                $this->info("Migrated: {$post->title}");
            } catch (\Throwable $e) {
                $this->error("Post \"{$post->title}\": {$e->getMessage()}");
                $skipped++;
            }
        }

        $this->newLine();
        $this->info("Done. Migrated: {$migrated}, Skipped: {$skipped}");
    }
}
