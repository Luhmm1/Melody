<h1 align="center">Melody</h1>

<p align="center">A lightweight and modular micro-framework based on a middleware architecture.</p>

<p align="center">
  <a href="https://github.com/Luhmm1/Melody/actions/workflows/tests.yml">
    <img src="https://github.com/Luhmm1/Melody/actions/workflows/tests.yml/badge.svg" alt="Tests">
  </a>
  <a href="https://packagist.org/packages/luhmm1/melody">
    <img src="https://flat.badgen.net/packagist/v/luhmm1/melody" alt="Version">
  </a>
  <a href="https://www.php.net/">
    <img src="https://flat.badgen.net/packagist/php/luhmm1/melody" alt="PHP">
  </a>
  <a href="https://github.com/Luhmm1/Melody/blob/master/LICENSE">
    <img src="https://flat.badgen.net/packagist/license/luhmm1/melody" alt="License">
  </a>
</p>

## Features

- Customizable PSR-7 and PSR-17 implementations.
- Integrated middleware dispatchers (FIFO or LIFO).
- Customizable middleware dispatcher.
- Customizable error handler.
- Integrated response emitter.
- Customizable response emitter.

## Installation

If you want to use a skeleton, check out [another of my projects](https://github.com/Luhmm1/Melody-Skeleton).

### 1. Prerequisites

- PHP (^8.0)
- Composer

### 2. Install PSR-7 and PSR-17 implementations

In order for Melody to maintain compatibility with most PHP libraries, it needs to use special objects provided by PSR-7 and PSR-17 implementations.

Here are the most popular implementations:

- [`guzzlehttp/psr7`](https://github.com/guzzle/psr7) & [`http-interop/http-factory-guzzle`](https://packagist.org/packages/http-interop/http-factory-guzzle)
- [`laminas/laminas-diactoros`](https://github.com/laminas/laminas-diactoros)
- [`nyholm/psr7`](https://github.com/Nyholm/psr7) & [`nyholm/psr7-server`](https://github.com/Nyholm/psr7-server)

### 3. Install Melody

You can install Melody using this Composer command:

```
composer require luhmm1/melody
```

## Usage

```php
use Melody\Application;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

$psr17 = new Psr17Factory();
$request = (new ServerRequestCreator($psr17, $psr17, $psr17, $psr17))->fromGlobals();

$app = new Application($request, $psr17);

// Write your application code here.

$app->run();
```

## Documentation

You can view the documentation on [our official website](https://melody.deville.dev/).
