<?php

declare(strict_types=1);

namespace Rohama\RichEditorEssentials\Filament\Plugins;

use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Tiptap\Core\Extension;

class LinkPlugin implements RichContentPlugin
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
        // Tiptap's built-in link mark already renders the `rel` attribute.
        return [];
    }

    /**
     * @return array<string>
     */
    public function getTipTapJsExtensions(): array
    {
        // Tiptap's built-in link mark already keeps the `rel` attribute.
        return [];
    }

    /**
     * @return array<RichEditorTool>
     */
    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('link')
                ->label(__('filament-forms::components.rich_editor.tools.link'))
                ->action(arguments: '{ url: $getEditor().getAttributes(\'link\')?.href, shouldOpenInNewTab: $getEditor().getAttributes(\'link\')?.target === \'_blank\', rel: $getEditor().getAttributes(\'link\')?.rel }')
                ->toggle()
                ->icon(Heroicon::Link)
                ->iconAlias('forms:components.rich-editor.toolbar.link'),
        ];
    }

    /**
     * @return array<Action>
     */
    public function getEditorActions(): array
    {
        return [
            Action::make('link')
                ->label(__('filament-forms::components.rich_editor.actions.link.label'))
                ->modalHeading(__('filament-forms::components.rich_editor.actions.link.modal.heading'))
                ->modalWidth(Width::Large)
                ->fillForm(fn (array $arguments): array => [
                    'url' => $arguments['url'] ?? null,
                    'shouldOpenInNewTab' => $arguments['shouldOpenInNewTab'] ?? false,
                    'isNofollow' => str_contains($arguments['rel'] ?? '', 'nofollow'),
                ])
                ->schema([
                    TextInput::make('url')
                        ->label(__('filament-forms::components.rich_editor.actions.link.modal.form.url.label'))
                        ->inputMode('url'),
                    Checkbox::make('shouldOpenInNewTab')
                        ->label(__('filament-forms::components.rich_editor.actions.link.modal.form.should_open_in_new_tab.label')),
                    Checkbox::make('isNofollow')
                        ->label(__('rich-editor-essentials::messages.link.nofollow')),
                ])
                ->action(function (array $arguments, array $data, RichEditor $component): void {
                    $isSingleCharacterSelection = ($arguments['editorSelection']['head'] ?? null) === ($arguments['editorSelection']['anchor'] ?? null);

                    if (blank($data['url'] ?? null)) {
                        $component->runCommands(
                            [
                                ...($isSingleCharacterSelection ? [EditorCommand::make(
                                    'extendMarkRange',
                                    arguments: ['link'],
                                )] : []),
                                EditorCommand::make('unsetLink'),
                            ],
                            editorSelection: $arguments['editorSelection'],
                        );

                        return;
                    }

                    // Keep any existing rel tokens (e.g. `noopener`) when toggling nofollow.
                    $rel = trim((string) preg_replace('/\bnofollow\b/', '', (string) ($arguments['rel'] ?? '')));

                    if ($data['isNofollow'] ?? false) {
                        $rel = trim($rel.' nofollow');
                    }

                    $component->runCommands(
                        [
                            ...($isSingleCharacterSelection ? [EditorCommand::make(
                                'extendMarkRange',
                                arguments: ['link'],
                            )] : []),
                            EditorCommand::make(
                                'setLink',
                                arguments: [[
                                    'href' => $data['url'],
                                    'target' => $data['shouldOpenInNewTab'] ? '_blank' : null,
                                    'rel' => $rel !== '' ? $rel : null,
                                ]],
                            ),
                        ],
                        editorSelection: $arguments['editorSelection'],
                    );
                }),
        ];
    }
}
