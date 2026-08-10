<?php

declare(strict_types=1);

namespace Rohama\RichEditorEssentials\Filament\Extensions;

use Tiptap\Core\Extension;

class TextDirection extends Extension
{
    public static $name = 'customTextDirection';

    public function addOptions(): array
    {
        return [
            'types' => ['paragraph', 'heading'],
        ];
    }

    public function addGlobalAttributes(): array
    {
        return [
            [
                'types' => $this->options['types'],
                'attributes' => [
                    'dir' => [
                        'default' => null,
                        'parseHTML' => fn ($element) => $element->getAttribute('dir'),
                        'renderHTML' => fn ($attributes) => $attributes->dir ? ['dir' => $attributes->dir] : [],
                    ],
                ],
            ],
        ];
    }
}
