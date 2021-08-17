<?php declare(strict_types = 1);

namespace Orisai\Utils\Reflection;

use ReflectionClass;
use function array_pop;
use function array_values;
use function class_parents;
use function dirname;
use function explode;

final class Classes
{

	/**
	 * @param class-string $class
	 * @return array<int, class-string>
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

		return dirname($reflection->getFileName());
	}

	public static function getShortName(string $class): string
	{
		$parts = explode('\\', $class);

		return array_pop($parts);
	}

}
