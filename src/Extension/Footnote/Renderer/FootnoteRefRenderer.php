<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 * (c) Rezo Zero / Ambroise Maupate
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace League\CommonMark\Extension\Footnote\Renderer;

use League\CommonMark\Configuration\ConfigurationAwareInterface;
use League\CommonMark\Configuration\ConfigurationInterface;
use League\CommonMark\Extension\Footnote\Node\FootnoteRef;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

final class FootnoteRefRenderer implements NodeRendererInterface, ConfigurationAwareInterface
{
    /** @var ConfigurationInterface */
    private $config;

    /**
     * {@inheritDoc}
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (! ($node instanceof FootnoteRef)) {
            throw new \InvalidArgumentException('Incompatible node type: ' . \get_class($node));
        }

        $attrs    = $node->getData('attributes', []);
        $class    = $attrs['class'] ?? $this->config->get('footnote/ref_class', 'footnote-ref');
        $idPrefix = $this->config->get('footnote/ref_id_prefix', 'fnref:');

        return new HtmlElement(
            'sup',
            [
                'id' => $idPrefix . \mb_strtolower($node->getReference()->getLabel()),
            ],
            new HtmlElement(
                'a',
                [
                    'class' => $class,
                    'href'  => \mb_strtolower($node->getReference()->getDestination()),
                    'role'  => 'doc-noteref',
                ],
                $node->getReference()->getTitle()
            ),
            true
        );
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }
}
