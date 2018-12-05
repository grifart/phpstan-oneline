# PHPStan one-line error formatter

Compact and **clickable** PHPStan error output handler. Especially useful with [Awesome Console](https://github.com/anthraxx/intellij-awesome-console) (available in PHP Storm repositories).

So when you run for example:

```bash
phpstan analyze src/app -c phpstan-model.neon -l 7 --error-format oneline
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

## Usage

```bash
phpstan analyze -l max --configuration phpstan.neon --error-format oneline
```
