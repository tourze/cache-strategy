# Cache Strategy

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/cache-strategy.svg?style=flat-square)](https://packagist.org/packages/tourze/cache-strategy)
[![MIT License](https://img.shields.io/packagist/l/tourze/cache-strategy.svg?style=flat-square)](https://github.com/tourze/cache-strategy/blob/main/LICENSE)
[![PHP Version Require](https://img.shields.io/packagist/php-v/tourze/cache-strategy.svg?style=flat-square)](https://packagist.org/packages/tourze/cache-strategy)
[![Code Coverage](https://img.shields.io/codecov/c/github/tourze/cache-strategy.svg?style=flat-square)](https://codecov.io/gh/tourze/cache-strategy)

This package provides an interface for defining cache strategies that help determine which database queries should be cached.

## Features

- Simple interface for implementing custom cache strategies
- Built-in `NoCacheStrategy` implementation for basic usage
- Symfony DependencyInjection integration with autoconfiguration support
- No external dependencies other than Symfony DependencyInjection
- Compatible with PHP 8.2 and above

## Installation

You can install this package using Composer:

```bash
composer require tourze/cache-strategy
```

### Requirements

- PHP 8.2 or higher
- Symfony DependencyInjection 6.4 or higher

## Quick Start

The core of this package is the `CacheStrategy` interface which provides a method to determine whether a query should be cached:

```php
<?php

use Tourze\CacheStrategy\CacheStrategy;

// Using the included NoCacheStrategy which caches all queries
$strategy = new \Tourze\CacheStrategy\NoCacheStrategy();

// Query and parameters
$query = "SELECT * FROM users WHERE status = ?";
$params = ['active'];

// Determine if the query should be cached
if ($strategy->shouldCache($query, $params)) {
    // Cache the query result
}
```

## Usage

### CacheStrategy Interface

The `CacheStrategy` interface has a single method:

```php
public function shouldCache(string $query, array $params): bool;
```

This method takes a query string and parameter array, returning a boolean indicating whether the query should be cached.

### Predefined Strategies

The package includes one predefined strategy:

- `NoCacheStrategy`: A simple strategy that always returns `true`, indicating all queries should be cached.

### Custom Strategies

You can create your own cache strategies by implementing the `CacheStrategy` interface:

```php
<?php

namespace App\Cache;

use Tourze\CacheStrategy\CacheStrategy;

class SelectQueryStrategy implements CacheStrategy
{
    public function shouldCache(string $query, array $params): bool
    {
        // Only cache SELECT queries
        return str_starts_with(strtoupper(trim($query)), 'SELECT');
    }
}
```

### Symfony Integration

The `CacheStrategy` interface is marked with Symfony's `AutoconfigureTag` attribute, making it automatically configured as a service with the tag `doctrine.cache.entity_cache_strategy`:

```php
#[AutoconfigureTag(CacheStrategy::SERVICE_TAG)]
interface CacheStrategy
{
    const SERVICE_TAG = 'doctrine.cache.entity_cache_strategy';
    // ...
}
```

This allows you to use dependency injection to collect all cache strategy services in your application.

## Best Practices

- Create specific cache strategies for different types of queries
- Consider performance implications when implementing complex cache strategies
- Use different strategies for read and write operations
- Test your strategies with real-world query patterns

## Testing

The package includes unit tests. To run the tests:

```bash
./vendor/bin/phpunit packages/cache-strategy/tests
```

## Contributing

Contributions are welcome! Please see the following guidelines:

1. **Reporting Issues**: Please report bugs through the GitHub issue tracker
2. **Pull Requests**: 
    - Follow PSR-12 coding standards
    - Write tests for new features
    - Ensure all tests pass before submitting
    - Keep commits focused and well-documented
3. **Documentation**: Update README files when adding new features

## Changelog

### [0.0.1] - Initial Release
- Initial implementation of `CacheStrategy` interface
- `NoCacheStrategy` implementation
- Symfony DependencyInjection integration
- Comprehensive test coverage

## License

This package is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
