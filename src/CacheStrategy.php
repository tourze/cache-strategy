<?php

namespace Tourze\CacheStrategy;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: CacheStrategy::SERVICE_TAG)]
interface CacheStrategy
{
    const SERVICE_TAG = 'doctrine.cache.entity_cache_strategy';

    public function shouldCache(string $query, array $params): bool;
}
