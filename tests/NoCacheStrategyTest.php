<?php

namespace Tourze\CacheStrategy\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\CacheStrategy\NoCacheStrategy;

class NoCacheStrategyTest extends TestCase
{
    /**
     * 测试 NoCacheStrategy 的 shouldCache 方法始终返回 true
     */
    public function testShouldCacheAlwaysReturnsTrue(): void
    {
        $strategy = new NoCacheStrategy();

        // 测试空查询和空参数
        $this->assertTrue($strategy->shouldCache('', []));

        // 测试有查询和空参数
        $this->assertTrue($strategy->shouldCache('SELECT * FROM users', []));

        // 测试有查询和有参数
        $this->assertTrue($strategy->shouldCache(
            'SELECT * FROM users WHERE id = ?',
            [1]
        ));

        // 测试复杂查询和复杂参数
        $this->assertTrue($strategy->shouldCache(
            'SELECT u.*, p.* FROM users u JOIN profiles p ON u.id = p.user_id WHERE u.active = ? AND p.verified = ?',
            [true, true]
        ));
    }
}
