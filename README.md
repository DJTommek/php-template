# PHP-Template

Basic template for small-sized PHP Projects with few most used utils with as few dependency as possible.

# Installation

1. Click on "[Use this template](https://github.com/DJTommek/php-template/generate)" button on [DJTommek/php-template](https://github.com/DJTommek/php-template) Github repository page.
1. Fill up necessary details as you creating new repository
1. Run `composer install`
1. Open `index.php` in browser

ℹ Note: `composer install` and `composer update` will automatically generate `data/config.local.php` if not exists. You can change and override default values which are extended from `src/libs/DefaultConfig.php`

## PHPUnit testing

```
composer test
```
Contains only one dummy test to show, how to create more tests. Quick info: 
- folder structure should reflect `/src/libs` as much as possible 
- class names must have same name as file name: `class DummyTest` -> `DummyTest.php`  
- file name and classnames must contain suffix **Test**: `DummyTest.php` and `class DummyTest`
- methods has to have prefix **test**: `function testDummyVariable()`. 

See [phpunit.de](https://phpunit.de/) website for detailed info. 

## PHPStan static analysis tool

```
composer phpstan
```
Finding errors in app without running it and code style keeper.

See [phpstan.org](https://phpstan.org/) website for more info.
