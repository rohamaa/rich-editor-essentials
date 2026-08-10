<?php

declare(strict_types=1);

namespace Rohama\RichEditorEssentials\Filament\Plugins;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Icons\Heroicon;
use Rohama\RichEditorEssentials\Filament\Extensions\TextDirection;
use Tiptap\Core\Extension;

class TextDirectionPlugin implements RichContentPlugin
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
            app(TextDirection::class),
        ];
    }

    /**
     * @return array<string>
     */
    public function getTipTapJsExtensions(): array
    {
        return [
            FilamentAsset::getScriptSrc('rich-editor-essentials/text-direction', 'rohama/rich-editor-essentials'),
        ];
    }

    /**
     * @return array<RichEditorTool>
     */
    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('ltr')
                ->label(__('LTR'))
                ->jsHandler('$getEditor()?.chain().focus().setTextDirection(\'ltr\').run()')
                ->activeJsExpression('$getEditor()?.isActive({ dir: \'ltr\' })')
                ->icon(Heroicon::Bars3BottomLeft),
            RichEditorTool::make('rtl')
                ->label(__('RTL'))
                ->jsHandler('$getEditor()?.chain().focus().setTextDirection(\'rtl\').run()')
                ->activeJsExpression('$getEditor()?.isActive({ dir: \'rtl\' })')
                ->icon(Heroicon::Bars3BottomRight),
        ];
    }

    /**
     * @return array<Action>
     */
    public function getEditorActions(): array
    {
        return [];
    }
}
