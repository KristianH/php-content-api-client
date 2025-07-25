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

use Storyblok\Api\Domain\Value\Dto\Pagination;
use Storyblok\Api\Domain\Value\Dto\SortBy;
use Storyblok\Api\Domain\Value\Dto\Version;
use Storyblok\Api\Domain\Value\Field\FieldCollection;
use Storyblok\Api\Domain\Value\Filter\FilterCollection;
use Storyblok\Api\Domain\Value\IdCollection;
use Storyblok\Api\Domain\Value\Resolver\RelationCollection;
use Storyblok\Api\Domain\Value\Resolver\ResolveLinks;
use Storyblok\Api\Domain\Value\Slug\Slug;
use Storyblok\Api\Domain\Value\Slug\SlugCollection;
use Storyblok\Api\Domain\Value\Tag\TagCollection;
use Webmozart\Assert\Assert;

/**
 * @author Silas Joisten <silasjoisten@proton.me>
 */
final readonly class StoriesRequest
{
    public const int MAX_PER_PAGE = 100;
    public const int PER_PAGE = 25;

    public function __construct(
        public string $language = 'default',
        public Pagination $pagination = new Pagination(perPage: self::PER_PAGE),
        public ?SortBy $sortBy = null,
        public FilterCollection $filters = new FilterCollection(),
        public FieldCollection $excludeFields = new FieldCollection(),
        public TagCollection $withTags = new TagCollection(),
        public IdCollection $excludeIds = new IdCollection(),
        public RelationCollection $withRelations = new RelationCollection(),
        public ?Version $version = null,
        public ?string $searchTerm = null,
        public ResolveLinks $resolveLinks = new ResolveLinks(),
        public SlugCollection $excludeSlugs = new SlugCollection(),
        public ?Slug $startsWith = null,
    ) {
        Assert::stringNotEmpty($language);
        Assert::lessThanEq($this->pagination->perPage, self::MAX_PER_PAGE);
    }

    /**
     * @return array{
     *     language: string,
     *     page: int,
     *     per_page: int,
     *     sort_by?: string,
     *     filter_query?: list<mixed>,
     *     with_tag?: string,
     *     excluding_fields?: string,
     *     excluding_ids?: string,
     *     resolve_relations?: string,
     *     resolve_links?: string,
     *     resolve_links_level?: string,
     *     search_term?: string,
     *     version?: string,
     *     excluding_slugs?: string,
     *     starts_with?: string,
     * }
     */
    public function toArray(): array
    {
        $array = [
            'language' => $this->language,
            'page' => $this->pagination->page,
            'per_page' => $this->pagination->perPage,
        ];

        if (null !== $this->sortBy) {
            $array['sort_by'] = $this->sortBy->toString();
        }

        if ($this->filters->count() > 0) {
            $array['filter_query'] = $this->filters->toArray();
        }

        if ($this->withTags->count() > 0) {
            $array['with_tag'] = $this->withTags->toString();
        }

        if ($this->excludeFields->count() > 0) {
            $array['excluding_fields'] = $this->excludeFields->toString();
        }

        if ($this->excludeIds->count() > 0) {
            $array['excluding_ids'] = $this->excludeIds->toString();
        }

        if ($this->withRelations->count() > 0) {
            $array['resolve_relations'] = $this->withRelations->toString();
        }

        if (null !== $this->resolveLinks->type) {
            $array['resolve_links'] = $this->resolveLinks->type->value;
            $array['resolve_links_level'] = $this->resolveLinks->level->value;
        }

        if (null !== $this->searchTerm) {
            $array['search_term'] = $this->searchTerm;
        }

        if (null !== $this->version) {
            $array['version'] = $this->version->value;
        }

        if ($this->excludeSlugs->count() > 0) {
            $array['excluding_slugs'] = $this->excludeSlugs->toString();
        }

        if (null !== $this->startsWith) {
            $array['starts_with'] = $this->startsWith->value;
        }

        return $array;
    }
}
