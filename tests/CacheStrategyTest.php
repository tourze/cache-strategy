<?php

namespace Tourze\CacheStrategy\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CacheStrategy\CacheStrategy;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * 测试 CacheStrategy 接口的常量定义
 *
 * @internal
 */
#[CoversClass(CacheStrategy::class)]
#[RunTestsInSeparateProcesses]
final class CacheStrategyTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 这个测试不需要额外的设置
    }

    public function testServiceTagConstant(): void
    {
        $this->assertSame('doctrine.cache.entity_cache_strategy', CacheStrategy::SERVICE_TAG);
    }
}
