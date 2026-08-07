<?php

declare(strict_types=1);

namespace Rohama\RichEditorEssentials\Filament\Extensions;

use Tiptap\Core\Node;

class Video extends Node
{
    public static $name = 'video';

    public function addOptions(): array
    {
        return [
            'width' => 16,
            'height' => 9,
        ];
    }

    public function addAttributes(): array
    {
        return [
            'src' => [
                'default' => null,
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('src'),
            ],
            'width' => [
                'default' => $this->options['width'],
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('width'),
            ],
            'height' => [
                'default' => $this->options['height'],
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('height'),
            ],
            'responsive' => [
                'default' => true,
                'parseHTML' => fn ($DOMNode) => str_contains((string) $DOMNode->getAttribute('class'), 'responsive'),
            ],
            'controls' => [
                'default' => true,
                'parseHTML' => fn ($DOMNode) => $DOMNode->hasAttribute('controls'),
            ],
            'autoplay' => [
                'default' => false,
                'parseHTML' => fn ($DOMNode) => $DOMNode->hasAttribute('autoplay'),
            ],
            'muted' => [
                'default' => false,
                'parseHTML' => fn ($DOMNode) => $DOMNode->hasAttribute('muted'),
            ],
            'loop' => [
                'default' => false,
                'parseHTML' => fn ($DOMNode) => $DOMNode->hasAttribute('loop'),
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'div[data-video] video',
            ],
            [
                'tag' => 'video[src]',
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        return [
            'div',
            [
                'data-video' => true,
                'class' => 'video',
            ],
            [
                'video',
                [
                    'src' => $node->attrs->src,
                    'width' => $node->attrs->width,
                    'height' => $node->attrs->height,
                    'controls' => $node->attrs->controls ? 'true' : null,
                    'autoplay' => $node->attrs->autoplay ? 'true' : null,
                    'muted' => ($node->attrs->autoplay || $node->attrs->muted) ? 'true' : null,
                    'loop' => $node->attrs->loop ? 'true' : null,
                    'playsinline' => $node->attrs->autoplay ? 'true' : null,
                    'preload' => $node->attrs->autoplay ? 'metadata' : null,
                    'class' => $node->attrs->responsive ? 'responsive' : null,
                    'style' => $node->attrs->responsive
                        ? "aspect-ratio:{$node->attrs->width}/{$node->attrs->height}; width: 100%; height: auto;"
                        : null,
                ],
            ],
        ];
    }
}
