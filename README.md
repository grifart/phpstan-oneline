# PHPStan one-line error formatter

Compact and **clickable** [PhpStan](http://github.com/phpstan/phpstan) error output handler. Especially useful with [Awesome Console](https://github.com/anthraxx/intellij-awesome-console) (available in PhpStorm repositories).

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
