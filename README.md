# Fuel Validation Package.

## Overview
This Package provide Validation class with Multi-byte Validations.

## Installing

Simply add `validation` to your config.php `always_loaded.packages` config option.

## Usage

```php:fuel/app/bootstrap.php
\Autoloader::add_classes(array(
	// Add classes you want to override here
	// Example: 'View' => APPPATH.'classes/view.php',
	'Validation' => APPPATH.'classes/validation.php',
));

```

```php:fuel/app/classes/validation.php
class Validation extends \Fuel\Validation\Validation
{
	// input your validations
}
```
