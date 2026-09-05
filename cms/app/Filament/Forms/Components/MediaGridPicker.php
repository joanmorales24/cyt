<?php

namespace App\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaGridPicker extends Field
{
    protected string $view = 'filament.forms.components.media-grid-picker';

    protected array|Closure $collectionNames = ['featured_image', 'default'];

    public function collections(array $collectionNames): static
    {
        $this->collectionNames = $collectionNames;

        return $this;
    }

    public function getItems(): array
    {
        $collectionNames = $this->evaluate($this->collectionNames);

        return Media::query()
            ->whereIn('collection_name', $collectionNames)
            ->latest()
            ->limit(300)
            ->get()
            ->map(fn (Media $media) => [
                'id' => $media->id,
                'url' => $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl(),
                'name' => $media->name,
            ])
            ->all();
    }
}
