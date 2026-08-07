<?php

declare(strict_types=1);

namespace Rohama\RichEditorEssentials\Filament\Plugins;

use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Icons\Heroicon;
use Rohama\RichEditorEssentials\Filament\Extensions\Video;
use Tiptap\Core\Extension;

class VideoPlugin implements RichContentPlugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * @return array<Extension>
     */
    public function getTipTapPhpExtensions(): array
    {
        return [
            app(Video::class),
        ];
    }

    /**
     * @return array<string>
     */
    public function getTipTapJsExtensions(): array
    {
        return [
            FilamentAsset::getScriptSrc('rich-editor-essentials/video', 'rohama/rich-editor-essentials'),
        ];
    }

    /**
     * @return array<RichEditorTool>
     */
    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('video')
                ->label(__('Video'))
                ->action()
                ->icon(Heroicon::OutlinedFilm)
                ->iconAlias('filament:toolbar.video'),
        ];
    }

    /**
     * @return array<Action>
     */
    public function getEditorActions(): array
    {
        return [
            Action::make('video')
                ->modalWidth(Width::Large)
                ->fillForm([
                    'responsive' => true,
                    'options' => ['controls'],
                    'width' => 16,
                    'height' => 9,
                ])
                ->schema([
                    TextInput::make('src')
                        ->label(__('Video URL'))
                        ->placeholder('https://example.com/video.mp4')
                        ->live()
                        ->required()
                        ->url(),
                    CheckboxList::make('options')
                        ->hiddenLabel()
                        ->gridDirection('row')
                        ->columns(3)
                        ->options([
                            'controls' => __('Controls'),
                            'autoplay' => __('Autoplay'),
                            'loop' => __('Loop'),
                        ])
                        ->dehydrateStateUsing(fn (Get $get, $state): array => [
                            'controls' => in_array('controls', $state) ? 1 : 0,
                            'autoplay' => in_array('autoplay', $state) ? 1 : 0,
                            'loop' => in_array('loop', $state) ? 1 : 0,
                        ]),
                    Checkbox::make('responsive')
                        ->default(true)
                        ->live()
                        ->label(__('Responsive'))
                        ->afterStateUpdated(function (callable $set, $state): void {
                            if ($state) {
                                $set('width', '16');
                                $set('height', '9');
                            } else {
                                $set('width', '640');
                                $set('height', '480');
                            }
                        })
                        ->columnSpan('full'),
                    Group::make([
                        TextInput::make('width')
                            ->live()
                            ->required()
                            ->label(__('Width'))
                            ->default('16'),
                        TextInput::make('height')
                            ->live()
                            ->required()
                            ->label(__('Height'))
                            ->default('9'),
                    ])->columns(['md' => 2]),
                ])
                ->action(function (array $arguments, array $data, RichEditor $component): void {
                    $options = $data['options'] ?? [];

                    $component->runCommands(
                        [
                            EditorCommand::make('setVideo', arguments: [[
                                'src' => $data['src'],
                                'width' => $data['width'],
                                'height' => $data['height'],
                                'responsive' => (bool) $data['responsive'],
                                'controls' => (bool) ($options['controls'] ?? false),
                                'autoplay' => (bool) ($options['autoplay'] ?? false),
                                'loop' => (bool) ($options['loop'] ?? false),
                            ]]),
                        ],
                        editorSelection: $arguments['editorSelection'],
                    );
                }),
        ];
    }
}
