<?php

namespace Tourze\CacheStrategy;

class NoCacheStrategy implements CacheStrategy
{
    public function shouldCache(string $query, array $params): bool
    {
        return true;
    }
}
