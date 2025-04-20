<?php

namespace Tourze\CacheStrategy\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\CacheStrategy\CacheStrategy;

/**
 * 这个测试类用于验证 CacheStrategy 接口的规范
 */
class CacheStrategyInterfaceTest extends TestCase
{
    /**
     * 测试 SERVICE_TAG 常量的值是否正确
     */
    public function testServiceTagConstant(): void
    {
        $this->assertSame('doctrine.cache.entity_cache_strategy', CacheStrategy::SERVICE_TAG);
    }

    /**
     * 测试实现了该接口的类必须有 shouldCache 方法
     */
    public function testInterfaceRequiresShouldCacheMethod(): void
    {
        // 使用反射检查接口方法
        $reflectionInterface = new \ReflectionClass(CacheStrategy::class);
        $methods = $reflectionInterface->getMethods();

        // 断言接口中有且只有一个方法
        $this->assertCount(1, $methods);

        // 断言这个方法名为 shouldCache
        $method = $methods[0];
        $this->assertSame('shouldCache', $method->getName());

        // 验证方法的参数类型
        $parameters = $method->getParameters();
        $this->assertCount(2, $parameters);

        // 验证第一个参数名和类型
        $this->assertSame('query', $parameters[0]->getName());
        $this->assertSame('string', $parameters[0]->getType()->getName());

        // 验证第二个参数名和类型
        $this->assertSame('params', $parameters[1]->getName());
        $this->assertSame('array', $parameters[1]->getType()->getName());

        // 验证返回类型是 bool
        $this->assertSame('bool', $method->getReturnType()->getName());
    }

    /**
     * 测试接口是否正确使用了 AutoconfigureTag 注解
     */
    public function testInterfaceHasAutoconfigureTagAttribute(): void
    {
        $reflectionInterface = new \ReflectionClass(CacheStrategy::class);
        $attributes = $reflectionInterface->getAttributes();

        // 断言有且只有一个属性
        $this->assertCount(1, $attributes);

        // 断言这个属性的名称为 AutoconfigureTag
        $attributeName = $attributes[0]->getName();
        $this->assertStringContainsString('AutoconfigureTag', $attributeName);

        // 断言 AutoconfigureTag 的参数是 SERVICE_TAG 常量
        $attributeArguments = $attributes[0]->getArguments();
        $this->assertCount(1, $attributeArguments);
        $this->assertSame(CacheStrategy::SERVICE_TAG, $attributeArguments[0]);
    }
}
