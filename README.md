# PHP Notify

Simple log package.

## Installation

```shell
composer require prinx/notify
```

## Usage

```php
$logger = new Log($file);
$logger->info('User 1 has logged in.');
```

## Log Levels

- debug
- info
- notice
- warning
- error
- critical
- alert
- emergency

Each log level corresponds to a method to log a message at that level.

```php
$logger->debug('Debug level event has happened.');

$logger->info('Info level event has happened.');

$logger->notice('Notice level event has happened.');

$logger->warning('Warning level event has happened.');

$logger->error('Error level event has happened.');

$logger->critical('Critical level event has happened.');

$logger->alert('Alert level event has happened.');

$logger->emergency('Emergency level event has happened.');
```

## Set another log file

```php
$logger->setFile('path/to/log/file');
```

## Remove log file

```php
$logger->remove();
```

## Running tests

```shell
php vendor/phpunit/phpunit/phpunit
```

## Contribute

The package supports only files. It will be nice to add other log dirvers.

Apart from that, feel free to create a pull request with a new functionality to the package.

## Licence

MIT
