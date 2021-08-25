# Prove PHP SDK

This project is heavily inspired by [Graham Campbell's](https://github.com/GrahamCampbell) packages; [Bitbucket](https://github.com/BitbucketPHP/Client) and [GitLab](https://github.com/GitLabPHP/Client). 

## Installation

This version supports PHP 7.2-8.0. To get started, require the project using [Composer](https://getcomposer.org). You will also need to install packages that provide [`psr/http-client-implementation`](https://packagist.org/providers/psr/http-client-implementation) and [`psr/http-factory-implementation`](https://packagist.org/providers/psr/http-factory-implementation).

### Standard Installation

```shell
composer require "prove/php-sdk:dev-master" "guzzlehttp/guzzle:^7.2" "http-interop/http-factory-guzzle:^1.0"
```

### Laravel Installation

```shell
composer require "prove/laravel:dev-master" "guzzlehttp/guzzle:^7.2" "http-interop/http-factory-guzzle:^1.0"
```

## Usage

```php
// Authentication
$client = new Prove\Client();
$client->authenticate('your_api_token');

// Example API Call
$experiment = $client->teams($teamId)->experiments()->show('MY_EXPERIMENT_KEY');
```

### Example with Pager

The `Pager` class allows you to easily retrieve all results across multiple pages of results.

```php
$pager = new Prove\ResultPager($client);
$experiments = $pager->fetchAll($client->teams($teamId)->experiments(), 'all');
```

## Security

If you discover a security vulnerability within this package, please send an email to James Brooks at james@prove.dev. All security vulnerabilities will be promptly addressed. You may view our full security policy [here](https://github.com/ProveApp/php-sdk/security/policy).

## License

Prove PHP SDK is licensed under [The MIT License (MIT)](LICENSE).
