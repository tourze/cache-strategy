<?php

namespace Tourze\CacheStrategy;

class NoCacheStrategy implements CacheStrategy
{
    /**
     * @param array<string, mixed> $params
     */
    public function shouldCache(string $query, array $params): bool
    {
        return true;
    }
}
