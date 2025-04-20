# Cache Strategy

这个包提供了一个用于定义缓存策略的接口，可以帮助确定哪些数据库查询应该被缓存。

## 安装

```bash
composer require tourze/cache-strategy
```

## 使用方法

这个包定义了 `CacheStrategy` 接口，你可以实现这个接口来创建自定义的缓存策略。

### CacheStrategy 接口

```php
interface CacheStrategy
{
    public function shouldCache(string $query, array $params): bool;
}
```

接口中的 `shouldCache` 方法接收查询字符串和参数数组，返回一个布尔值表示该查询是否应该被缓存。

### 预定义策略

该包包含了一个预定义的缓存策略：

- `NoCacheStrategy`: 一个始终返回 `true` 的策略，表示所有查询都应该被缓存。

### 自定义策略

你可以通过实现 `CacheStrategy` 接口来创建自己的缓存策略：

```php
use Tourze\CacheStrategy\CacheStrategy;

class MyCustomStrategy implements CacheStrategy
{
    public function shouldCache(string $query, array $params): bool
    {
        // 实现你的逻辑，决定是否缓存查询
        // 例如：只缓存 SELECT 查询
        return str_starts_with(strtoupper(trim($query)), 'SELECT');
    }
}
```

## 与 Symfony DependencyInjection 集成

`CacheStrategy` 接口使用了 Symfony 的 `AutoconfigureTag` 属性，这使得它可以自动配置为服务。接口定义了常量 `SERVICE_TAG` 作为服务标签的名称：

```php
const SERVICE_TAG = 'doctrine.cache.entity_cache_strategy';
```

这使得 Symfony 的依赖注入容器可以自动标记实现了 `CacheStrategy` 接口的服务。

## 测试

包内含一组单元测试。你可以通过以下命令运行测试：

```bash
./vendor/bin/phpunit packages/cache-strategy/tests
```
