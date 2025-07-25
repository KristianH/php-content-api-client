<?php

declare(strict_types=1);

/**
 * This file is part of storyblok/php-content-api-client.
 *
 * (c) Storyblok GmbH <info@storyblok.com>
 * in cooperation with SensioLabs Deutschland <info@sensiolabs.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Storyblok\Api\Request;

use Storyblok\Api\Domain\Value\Dto\Version;
use Storyblok\Api\Domain\Value\Resolver\RelationCollection;
use Storyblok\Api\Domain\Value\Resolver\ResolveLinks;
use Webmozart\Assert\Assert;

/**
 * @author Silas Joisten <silasjoisten@proton.me>
 */
final readonly class StoryRequest
{
    public function __construct(
        public string $language = 'default',
        public ?Version $version = null,
        public RelationCollection $withRelations = new RelationCollection(),
        public ResolveLinks $resolveLinks = new ResolveLinks(),
    ) {
        Assert::stringNotEmpty($language);
    }

    /**
     * @return array{
     *     language: string,
     *     version?: string,
     *     resolve_relations?: string,
     *     resolve_links?: string,
     *     resolve_links_level?: int,
     * }
     */
    public function toArray(): array
    {
        $array = [
            'language' => $this->language,
        ];

        if (null !== $this->version) {
            $array['version'] = $this->version->value;
        }

        if ($this->withRelations->count() > 0) {
            $array['resolve_relations'] = $this->withRelations->toString();
        }

        if (null !== $this->resolveLinks->type) {
            $array['resolve_links'] = $this->resolveLinks->type->value;
            $array['resolve_links_level'] = $this->resolveLinks->level->value;
        }

        return $array;
    }
}
