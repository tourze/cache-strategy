<?php

namespace Tourze\CacheStrategy\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\CacheStrategy\CacheStrategy;

/**
 * 自定义缓存策略实现，用于测试目的
 */
class CustomCacheStrategy implements CacheStrategy
{
    public function shouldCache(string $query, array $params): bool
    {
        // 只缓存不包含 UPDATE、INSERT、DELETE 的查询
        $upperQuery = strtoupper($query);
        return !(
            str_contains($upperQuery, 'UPDATE') ||
            str_contains($upperQuery, 'INSERT') ||
            str_contains($upperQuery, 'DELETE')
        );
    }
}

/**
 * 测试自定义的 CacheStrategy 实现
 */
class CustomCacheStrategyTest extends TestCase
{
    private CustomCacheStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new CustomCacheStrategy();
    }

    /**
     * 测试应该被缓存的查询
     */
    public function testQueriesThatShouldBeCached(): void
    {
        // SELECT 查询应该被缓存
        $this->assertTrue($this->strategy->shouldCache('SELECT * FROM users', []));
        $this->assertTrue($this->strategy->shouldCache('select * from users', []));
        $this->assertTrue($this->strategy->shouldCache('SELECT u.* FROM users u WHERE u.id = ?', [1]));

        // 复杂的 SELECT 查询也应该被缓存
        $this->assertTrue($this->strategy->shouldCache(
            'SELECT u.*, p.* FROM users u JOIN profiles p ON u.id = p.user_id WHERE u.active = ?',
            [true]
        ));

        // 空查询也应该被缓存
        $this->assertTrue($this->strategy->shouldCache('', []));
    }

    /**
     * 测试不应该被缓存的查询
     */
    public function testQueriesThatShouldNotBeCached(): void
    {
        // UPDATE 查询不应该被缓存
        $this->assertFalse($this->strategy->shouldCache('UPDATE users SET name = ? WHERE id = ?', ['John', 1]));

        // INSERT 查询不应该被缓存
        $this->assertFalse($this->strategy->shouldCache('INSERT INTO users (name) VALUES (?)', ['John']));

        // DELETE 查询不应该被缓存
        $this->assertFalse($this->strategy->shouldCache('DELETE FROM users WHERE id = ?', [1]));

        // 包含这些关键字的复杂查询也不应该被缓存
        $this->assertFalse($this->strategy->shouldCache(
            'SELECT * FROM users WHERE id IN (SELECT id FROM users_to_delete); DELETE FROM users WHERE id IN (SELECT id FROM users_to_delete);',
            []
        ));
    }
}
