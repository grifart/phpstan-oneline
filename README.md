# PHPStan on-line error formatter

Compact and **clickable** PHPStan error output handler. Especially useful with [Awesome Console](https://github.com/anthraxx/intellij-awesome-console) (available in PHP Storm repositories).

`<image here>`

## Installation

```bash
composer require --dev grifart/phpstan-oneline
```

and register error formatter into your phpstan config:

```neon
includes:
	- vendor/grifart/phpstan-oneline/config.neon
```

## Usage

```bash
phpstan analyze -l max --error-format oneline
```
