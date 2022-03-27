<?php declare(strict_types = 1);

namespace Orisai\Utils\Reflection;

use ReflectionClass;
use function array_pop;
use function array_values;
use function class_exists;
use function class_parents;
use function dirname;
use function explode;
use function str_replace;
use const DIRECTORY_SEPARATOR;

final class Classes
{

	/**
	 * @template T
	 * @param class-string<T> $class
	 * @return array<int, class-string<T>>
	 */
	public static function getClassList(string $class): array
	{
		return array_values([$class] + class_parents($class));
	}

	/**
	 * @param class-string $class
	 */
	public static function getClassDir(string $class): string
	{
		$reflection = new ReflectionClass($class);

		$dir = dirname($reflection->getFileName());

		if (DIRECTORY_SEPARATOR !== '/') {
			return str_replace(DIRECTORY_SEPARATOR, '/', $dir);
		}

		return $dir;
	}

	public static function getShortName(string $class): string
	{
		$parts = explode('\\', $class);

		return array_pop($parts);
	}

	/**
	 * @param class-string $class
	 * @phpstan-return '::'|'->'
	 */
	public static function getMethodOperator(string $class, string $function): string
	{
		if (!class_exists($class)) {
			return '::';
		}

		$ref = new ReflectionClass($class);
		if (!$ref->hasMethod($function)) {
			return '::';
		}

		return $ref->getMethod($function)->isStatic()
			? '::'
			: '->';
	}

}
