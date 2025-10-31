<?php

namespace Tourze\CacheStrategy\Tests;

use Tourze\CacheStrategy\CacheStrategy;

/**
 * 自定义缓存策略实现，用于测试目的
 */
class CustomCacheStrategy implements CacheStrategy
{
    /**
     * @param array<string, mixed> $params
     */
    public function shouldCache(string $query, array $params): bool
    {
        // 只缓存不包含 UPDATE、INSERT、DELETE 的查询
        $upperQuery = strtoupper($query);

        return !(
            str_contains($upperQuery, 'UPDATE')
            || str_contains($upperQuery, 'INSERT')
            || str_contains($upperQuery, 'DELETE')
        );
    }
}
