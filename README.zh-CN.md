# 缓存策略

[![Latest Version](https://img.shields.io/packagist/v/tourze/cache-strategy.svg?style=flat-square)](https://packagist.org/packages/tourze/cache-strategy)
[![MIT License](https://img.shields.io/packagist/l/tourze/cache-strategy.svg?style=flat-square)](https://github.com/tourze/cache-strategy/blob/main/LICENSE)

本包提供了一个用于定义缓存策略的接口，帮助确定哪些数据库查询应该被缓存。

## 功能特性

- 简洁的接口设计，便于实现自定义缓存策略
- 内置 `NoCacheStrategy` 实现，可直接使用
- 与 Symfony 依赖注入容器无缝集成，支持自动配置
- 除 Symfony DependencyInjection 外无其他外部依赖
- 兼容 PHP 8.1 及以上版本

## 安装方法

你可以通过 Composer 安装此包：

```bash
composer require tourze/cache-strategy
```

### 系统要求

- PHP 8.1 或更高版本
- Symfony DependencyInjection 6.4 或更高版本

## 快速上手

这个包的核心是 `CacheStrategy` 接口，它提供了一个方法来确定是否应该缓存查询：

```php
<?php

use Tourze\CacheStrategy\CacheStrategy;

// 使用内置的 NoCacheStrategy，它会缓存所有查询
$strategy = new \Tourze\CacheStrategy\NoCacheStrategy();

// 查询和参数
$query = "SELECT * FROM users WHERE status = ?";
$params = ['active'];

// 判断是否应该缓存该查询
if ($strategy->shouldCache($query, $params)) {
    // 缓存查询结果
}
```

## 使用说明

### CacheStrategy 接口

`CacheStrategy` 接口只有一个方法：

```php
public function shouldCache(string $query, array $params): bool;
```

该方法接收查询字符串和参数数组，返回一个布尔值表示是否应该缓存该查询。

### 预定义策略

该包包含一个预定义的缓存策略：

- `NoCacheStrategy`: 一个简单的策略，始终返回 `true`，表示所有查询都应该被缓存。

### 自定义策略

你可以通过实现 `CacheStrategy` 接口来创建自己的缓存策略：

```php
<?php

namespace App\Cache;

use Tourze\CacheStrategy\CacheStrategy;

class SelectQueryStrategy implements CacheStrategy
{
    public function shouldCache(string $query, array $params): bool
    {
        // 只缓存 SELECT 查询
        return str_starts_with(strtoupper(trim($query)), 'SELECT');
    }
}
```

### Symfony 集成

`CacheStrategy` 接口使用了 Symfony 的 `AutoconfigureTag` 属性，使其能够自动配置为带有 `doctrine.cache.entity_cache_strategy` 标签的服务：

```php
#[AutoconfigureTag(CacheStrategy::SERVICE_TAG)]
interface CacheStrategy
{
    const SERVICE_TAG = 'doctrine.cache.entity_cache_strategy';
    // ...
}
```

这使你可以使用依赖注入来收集应用程序中的所有缓存策略服务。

## 最佳实践

- 为不同类型的查询创建特定的缓存策略
- 实现复杂缓存策略时考虑性能影响
- 为读操作和写操作使用不同的策略
- 使用真实场景下的查询模式测试你的策略

## 测试

包中包含单元测试，可以通过以下命令运行测试：

```bash
./vendor/bin/phpunit packages/cache-strategy/tests
```

## 开源协议

本包使用 MIT 许可证 - 详见 [LICENSE](LICENSE) 文件。 
