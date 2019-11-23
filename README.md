# Laravel Simple JWT

[![Test Suite Status](https://github.com/larsgrevelink/laravel-simple-jwt/workflows/Test%20Suite/badge.svg)](https://github.com/larsgrevelink/laravel-simple-jwt)
[![Total Downloads](https://poser.pugx.org/lgrevelink/laravel-simple-jwt/d/total.svg)](https://packagist.org/packages/lgrevelink/laravel-simple-jwt)
[![Latest Stable Version](https://poser.pugx.org/lgrevelink/laravel-simple-jwt/v/stable.svg)](https://packagist.org/packages/lgrevelink/laravel-simple-jwt)
[![License](https://poser.pugx.org/lgrevelink/laravel-simple-jwt/license.svg)](https://github.com/larsgrevelink/laravel-simple-jwt)

Laravel and Lumen utilities to kickstart the usage of the [PHP Simple JWT](https://github.com/LarsGrevelink/php-simple-jwt) package.

Supported functionalities;

* Artisan make command for JWT Blueprints (`make:jwt-blueprint`)
* Validator rule for JWT validation


## Installation

```bash
composer require lgrevelink/laravel-simple-jwt
```


## For Laravel

Laravel's auto-discovery directly registers the service provider so it should be instantly usable. If you don't use auto-discovery, please add the `SimpleJwtServiceProvider` to the provider array in `config/app.php`.

```php
LGrevelink\LaravelSimpleJWT\Providers\SimpleJwtServiceProvider::class
```

Both the artisan command and the validator are directly registered by adding the service provider.


## For Lumen

Using this package in lumen requires you to register the service provider in `bootstrap/app.php`.

```php
$app->register(LGrevelink\LaravelSimpleJWT\Providers\SimpleJwtServiceProvider::class);
```

Auto-extending the validator rule **only** works when the Facades have been setup. This can be done by enabling the `withFacades` option in `bootstrap/app.php`. Aliases are not necessary.

```php
$app->withFacades();
```


## Usage


### Generator command

After getting set up, generating your first Blueprint is very easy.

```php
php artisan make:jwt-blueprint MyFirstBlueprint
```

A series of questions are following setting up the default claims supported by JWT. Values you don't want to use can be left empty and won't be added. The default namespace where the blueprints will be placed is `App\JwtTokens`.

All date-related claims (e.g. expiration time) are treated as relative values from the moment generation. Find more information about the Simple JWT package and its usage [here](https://github.com/LarsGrevelink/php-simple-jwt).


### Validator

There are 2 general ways of using the validator rule.

**Basic usage**

```php
$request->validate([
    'foo' => 'jwt',
    'bar' => new ValidJwt(),
]);
```

This only validates whether the token has a valid JWT structure.


**Blueprint usage**

```php
$request->validate([
    'foo' => 'jwt:' . App\JwtTokens\MyFirstBlueprint::class,
    'bar' => new ValidJwt(App\JwtTokens\MyFirstBlueprint::class),
]);
```

This validates the token directly against the given blueprint.


## Tests

Tests are written with PHPUnit and can be run via the following composer command;

```bash
composer run test
```
