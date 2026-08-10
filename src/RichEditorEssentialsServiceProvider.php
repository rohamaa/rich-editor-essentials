<?php

declare(strict_types=1);

namespace Rohama\RichEditorEssentials;

use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class RichEditorEssentialsServiceProvider extends ServiceProvider
{
    public static string $name = 'rich-editor-essentials';

    public static string $package = 'rohama/rich-editor-essentials';

    public function boot(): void
    {
        FilamentAsset::register([
            Js::make(
                id: static::$name.'/video',
                path: __DIR__.'/../resources/dist/video.js'
            )->loadedOnRequest(),
            Js::make(
                id: static::$name.'/text-direction',
                path: __DIR__.'/../resources/dist/TextDirection.js'
            )->loadedOnRequest(),
        ], static::$package);
    }
}
