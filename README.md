# PHPStan one-line error formatter

[![Latest Stable Version](https://poser.pugx.org/grifart/phpstan-oneline/v/stable)](https://packagist.org/packages/grifart/phpstan-oneline)
[![Total Downloads](https://poser.pugx.org/grifart/phpstan-oneline/downloads)](https://packagist.org/packages/grifart/phpstan-oneline)
[![License](https://poser.pugx.org/grifart/phpstan-oneline/license)](https://packagist.org/packages/grifart/phpstan-oneline)
[![Build Status](https://travis-ci.org/grifart/phpstan-oneline.svg?branch=master)](https://travis-ci.org/grifart/phpstan-oneline)

Compact and **clickable** [PhpStan](http://github.com/phpstan/phpstan) error output handler.

So when you run for example:

```bash
phpstan analyze -l max --configuration phpstan.neon --error-format oneline
```

![](example.png)

and now you will get to the location where error occurred by one-click!

## Installation

```bash
composer require --dev grifart/phpstan-oneline
```

and register error formatter into your `phpstan.neon`:

```neon
includes:
	- vendor/grifart/phpstan-oneline/config.neon
```

### Clickable paths in PhpStorm

1. Install [Awesome Console](https://github.com/anthraxx/intellij-awesome-console) (available in PhpStorm repositories)
2. run phpstan in PhpStorm terminal


## Custom error format

There has been added `compact` error format. It looks like this by default:

```bash
phpstan analyze -l max --configuration phpstan.neon --error-format compact
```

![](example-compact.png)

You can customize `compact` error format in your `phpstan.neon`:

```neon
parameters:
	compact:
		format: "{path}:{line}\n ↳ {error}" # default
```

