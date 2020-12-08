# Utils

Utility classes used across Orisai libraries

## Content

- [Optional dependencies](#optional-dependencies)
    - [Optional extension](#optional-extension)
    - [Optional package](#optional-package)
- [Arrays](#arrays)
    - [Merge arrays](#merge-arrays)

## Optional dependencies

Sometimes it's useful to have dependency required only by specific class, method or function.
In that case we should check whether the dependency is installed.

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

## Arrays

### Merge arrays

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
