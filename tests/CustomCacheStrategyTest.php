<?php

namespace Tourze\CacheStrategy\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

/**
 * 测试自定义的 CacheStrategy 实现
 *
 * @internal
 */
#[CoversClass(CustomCacheStrategy::class)]
#[CoversMethod(CustomCacheStrategy::class, 'shouldCache')]
final class CustomCacheStrategyTest extends TestCase
{
    private CustomCacheStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->strategy = new CustomCacheStrategy();
    }

    /**
     * 测试 shouldCache 方法
     */
    public function testShouldCache(): void
    {
        // 测试 shouldCache 方法的基本功能
        $this->assertTrue($this->strategy->shouldCache('SELECT * FROM users', []));
        $this->assertFalse($this->strategy->shouldCache('UPDATE users SET name = ?', []));
    }

    /**
     * 测试应该被缓存的查询
     */
    public function testQueriesThatShouldBeCached(): void
    {
        // SELECT 查询应该被缓存
        $this->assertTrue($this->strategy->shouldCache('SELECT * FROM users', []));
        $this->assertTrue($this->strategy->shouldCache('select * from users', []));
        $this->assertTrue($this->strategy->shouldCache('SELECT u.* FROM users u WHERE u.id = ?', []));

        // 复杂的 SELECT 查询也应该被缓存
        $this->assertTrue($this->strategy->shouldCache(
            'SELECT u.*, p.* FROM users u JOIN profiles p ON u.id = p.user_id WHERE u.active = ?',
            []
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
        $this->assertFalse($this->strategy->shouldCache('UPDATE users SET name = ? WHERE id = ?', []));

        // INSERT 查询不应该被缓存
        $this->assertFalse($this->strategy->shouldCache('INSERT INTO users (name) VALUES (?)', []));

        // DELETE 查询不应该被缓存
        $this->assertFalse($this->strategy->shouldCache('DELETE FROM users WHERE id = ?', []));

        // 包含这些关键字的复杂查询也不应该被缓存
        $this->assertFalse($this->strategy->shouldCache(
            'SELECT * FROM users WHERE id IN (SELECT id FROM users_to_delete); DELETE FROM users WHERE id IN (SELECT id FROM users_to_delete);',
            []
        ));
    }
}
