# Laravel Tinker Server

[![Latest Version on Packagist](https://img.shields.io/packagist/v/redmoon/tinker-server.svg?style=flat-square)](https://packagist.org/packages/redmoon/tinker-server)
[![Build Status](https://img.shields.io/travis/rlunar/tinker-server/master.svg?style=flat-square)](https://travis-ci.org/rlunar/tinker-server)
[![StyleCI](https://github.styleci.io/repos/200523436/shield?branch=master)](https://github.styleci.io/repos/200523436)
[![Quality Score](https://img.shields.io/scrutinizer/g/rlunar/tinker-server.svg?style=flat-square)](https://scrutinizer-ci.com/g/rlunar/tinker-server)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rlunar/tinker-server/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rlunar/tinker-server/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/rlunar/tinker-server/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/rlunar/tinker-server/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/rlunar/tinker-server/badges/build.png?b=master)](https://scrutinizer-ci.com/g/rlunar/tinker-server/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/rlunar/tinker-server/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Total Downloads](https://img.shields.io/packagist/dt/redmoon/tinker-server.svg?style=flat-square)](https://packagist.org/packages/redmoon/tinker-server)

This package will give you a tinker server, that collects all your `tinker` call outputs **and** allows you to interact with the variables on the fly.

## Installation

You can install the package via composer:

```bash
composer require redmoon/tinker-server
```

The package will register itself automatically.

Optionally you can publish the package configuration using:

```bash
php artisan vendor:publish --provider=RedMoon\\TinkerServer\\TinkerServerServiceProvider
```

This will publish a file called `tinker-server.php` in your `config` folder.

In the config file, you can specify the dump server host that you want to listen on, in case you want to change the default value.

## Usage

Start the tinker server by calling the artisan command:

```bash
php artisan tinker-server
```

And then you can put `tinker` calls in your methods to dump variable content as well as instantly making them available in an interactive REPL shell.

```php
$user = App\User::find(1);

tinker($user);
```

In addition to the `tinker` method, there is also a `td` method, that behaves similar to `dd`. It tinkers the variable and dies the current request.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email rluna@webdcg.com instead of using the issue tracker.

## Credits

- [Roberto Luna Rojas](https://github.com/rlunar)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
