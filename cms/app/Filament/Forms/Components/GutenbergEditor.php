<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class GutenbergEditor extends Field
{
    protected string $view = 'filament.forms.components.gutenberg-editor';

    public function getUploadUrl(): string
    {
        return route('admin.upload-block-image');
    }
}
