# Utils

Utility classes used across Orisai libraries

## Content

- [Setup](#setup)
- [Optional dependencies](#optional-dependencies)
	- [Optional extension](#optional-extension)
	- [Optional package](#optional-package)
	- [Testing optionals](#testing-optionals)
- [Arrays](#arrays)
	- [Merge arrays](#merge-arrays)
- [Reflection](#reflection)

## Setup

Install with [Composer](https://getcomposer.org)

```sh
composer require orisai/utils
```

## Optional dependencies

Sometimes it's useful to have dependency required only by specific class, method or function. In that case we should
check whether the dependency is installed.

### Optional extension

```php
use Orisai\Utils\Dependencies\Dependencies;
use Orisai\Utils\Dependencies\Exception\ExtensionRequired;

$missing = Dependencies::getNotLoadedExtensions(['json', 'curl']);

if ($missing !== []) {
	throw ExtensionRequired::forClass($missing, static::class);
	throw ExtensionRequired::forMethod($missing, static::class, __FUNCTION__);
	throw ExtensionRequired::forFunction($missing,  __FUNCTION__);
}
```

You can also check whether a single extension is loaded

```php
use Orisai\Utils\Dependencies\Dependencies;

if (Dependencies::isExtensionLoaded('curl')) {
	// Do something
}
```

### Optional package

```php
use Orisai\Utils\Dependencies\Dependencies;
use Orisai\Utils\Dependencies\Exception\PackageRequired;

$missing = Dependencies::getNotLoadedPackages(['example/package1', 'example/package2']);

if ($missing !== []) {
	throw PackageRequired::forClass($missing, static::class);
	throw PackageRequired::forMethod($missing, static::class, __FUNCTION__);
	throw PackageRequired::forFunction($missing, __FUNCTION__);
}
```

If the class extends class, implements interface or uses trait from an optional package then exception must be thrown
before class is defined. Otherwise php fatal error is thrown.

```php
use Orisai\Utils\Dependencies\Exception\PackageRequired;

if (!class_exists(ClassFromOptionalDependency::class)) {
	throw PackageRequired::forUndefinedClass(['example/package'], Example::class, __FILE__);
}

class Example extends ClassFromOptionalDependency
{

}
```

You can also check whether a single package is loaded

```php
use Orisai\Utils\Dependencies\Dependencies;

if (Dependencies::isPackageLoaded('example/package')) {
	// Do something
}
```

### Testing optionals

For testing, you can emulate extension and packages are not loaded instead of having not tested code branches or
complicated CI.

Just make sure your optionals tests run in separate processes to prevent race conditions from parallel tests running.
e.g. with [PHPUnit](https://github.com/sebastianbergmann/phpunit) `@runInSeparateProcess` annotation.

Testing extensions:

```php
use Orisai\Utils\Dependencies\Dependencies;
use Orisai\Utils\Dependencies\DependenciesTester;

DependenciesTester::addIgnoredExtensions(['curl']);

Dependencies::getNotLoadedExtensions(['curl']); // ['curl']
Dependencies::isExtensionLoaded('curl'); // false
```

Testing packages:

```php
use Orisai\Utils\Dependencies\Dependencies;
use Orisai\Utils\Dependencies\DependenciesTester;

DependenciesTester::addIgnoredPackages(['example/package']);

Dependencies::getNotLoadedPackages(['example/package']); // ['example/package']
Dependencies::isPackageLoaded('example/package'); // false
```

## Arrays

### Merge arrays

Intuitive merging

- merging arrays recursively
- preferring non-default values in case of collision
- overriding string keys
- adding numeric keys on the end of array

```php
use Orisai\Utils\Arrays\ArrayMerger;

$default = [
	'default' => 'default',
	'overridden' => 'not-overridden',
	'merged' => [
		'foo' => 'not-overridden',
		'bar' => 'not-overridden',
		'baz' => 'not-overridden',
	],
];

$toMerge = [
	'overridden' => 'overridden',
	'new' => 'new',
	'merged' => [
		'baz' => 'overridden',
	],
];

$result = ArrayMerger::merge($default, $toMerge);

$expected = [
	'default' => 'default',
	'overridden' => 'overridden',
	'merged' => [
		'foo' => 'not-overridden',
		'bar' => 'not-overridden',
		'baz' => 'overridden',
	],
	'new' => 'new',
];

assert($result === $expected);
```

## Reflection

Shortcut functions wrapping behavior of php `Reflector` classes

- Use these shortcuts just for single-time reflection uses. For more intensive reflection use cases always use built-in
  Reflector classes.

Get list of classes of parent classes, including actual class

```php
use Orisai\Utils\Reflection\Classes;

$classList = Classes::getClassList(LogicException::class);

// $classList
[
	LogicException::class,
	Exception::class,
];
```

Get class directory

```php
use Orisai\Exceptions\DomainException;
use Orisai\Utils\Reflection\Classes;

$classDir = Classes::getClassDir(DomainException::class);

// $classDir
'/path/to/vendor/orisai/exceptions';
```

Get class name without namespace

```php
use Orisai\Exceptions\DomainException;
use Orisai\Utils\Reflection\Classes;

$classShortName = Classes::getShortName(DomainException::class);

// $classShortName
'DomainException';
```

Get method operator

```php
use Orisai\Utils\Reflection\Classes;

$operator = Classes::getMethodOperator(Example::class, 'staticMethod');

// $operator
'::';
```

```php
use Orisai\Utils\Reflection\Classes;

$operator = Classes::getMethodOperator(Example::class, 'objectMethod');

// $operator
'->';
```
