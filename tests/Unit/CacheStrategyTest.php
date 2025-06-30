<?php

namespace Tourze\CacheStrategy\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tourze\CacheStrategy\CacheStrategy;

/**
 * 测试 CacheStrategy 接口的常量定义
 */
class CacheStrategyTest extends TestCase
{
    public function testServiceTagConstant(): void
    {
        $this->assertSame('doctrine.cache.entity_cache_strategy', CacheStrategy::SERVICE_TAG);
    }
}