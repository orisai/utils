<?php declare(strict_types = 1);

namespace Orisai\Utils\Dependencies\Exception;

use Orisai\Exceptions\LogicalException;
use Orisai\Exceptions\Message;
use ReflectionClass;
use ReflectionFunction;
use function class_exists;
use function count;
use function dirname;
use function explode;
use function file_exists;
use function file_get_contents;
use function function_exists;
use function implode;
use function is_string;
use function json_decode;
use const JSON_THROW_ON_ERROR;
use const PHP_EOL;

final class PackageRequired extends LogicalException
{

	/**
	 * @param array<string> $packages
	 * @param class-string $class
	 */
	public static function forClass(array $packages, string $class): self
	{
		return self::create(
			$packages,
			"class {$class}",
			class_exists($class) ? new ReflectionClass($class) : null,
		);
	}

	/**
	 * @param array<string> $packages
	 * @param class-string $class
	 */
	public static function forMethod(array $packages, string $class, string $function): self
	{
		$operator = self::getMethodOperator($class, $function);

		return self::create(
			$packages,
			"method {$class}{$operator}{$function}()",
			class_exists($class) ? new ReflectionClass($class) : null,
		);
	}

	/**
	 * @param array<string> $packages
	 */
	public static function forFunction(array $packages, string $function): self
	{
		return self::create(
			$packages,
			"function {$function}()",
			function_exists($function) ? new ReflectionFunction($function) : null,
		);
	}

	/**
	 * @param array<string> $packages
	 */
	public static function forUndefinedClass(array $packages, string $class, string $file): self
	{
		return self::create($packages, "class {$class}", file_exists($file) ? $file : null);
	}

	/**
	 * @param array<string> $packages
	 * @param ReflectionClass<object>|ReflectionFunction|string|null $source
	 */
	private static function create(array $packages, string $called, $source): self
	{
		$self = new self();

		foreach ($packages as $key => $package) {
			$split = explode(':', $package, 2);
			$packageName = $split[0];
			$packageVersion = $split[1] ?? self::getPackageVersion($packageName, $source);

			if ($packageVersion !== null) {
				$packages[$key] = "\"{$packageName}:{$packageVersion}\"";
			}
		}

		$packagesInlineMessage = implode(', ', $packages);
		$required = count($packages) > 1
			? "s {$packagesInlineMessage} are"
			: " {$packagesInlineMessage} is";

		$packagesInlineCommand = implode(' ', $packages);

		$message = Message::create()
			->withContext("Trying to use {$called}.")
			->withProblem("Required package{$required} not installed.")
			->withSolution('Install with' . PHP_EOL . ">>>composer require {$packagesInlineCommand}<<<");
		$self->withMessage($message);

		return $self;
	}

	/**
	 * @param ReflectionClass<object>|ReflectionFunction|string|null $source
	 */
	private static function getPackageVersion(string $packageName, $source): ?string
	{
		if ($source === null) {
			return null;
		}

		$fileName = is_string($source)
			? $source
			: $source->getFileName();

		$content = $fileName !== false
			? self::getComposerJsonContent(dirname($fileName))
			: [];

		return $content['require-dev'][$packageName] ?? null;
	}

	/**
	 * @return array<mixed>
	 */
	private static function getComposerJsonContent(string $path): array
	{
		$composerJson = "{$path}/composer.json";

		if (file_exists($composerJson)) {
			return json_decode(file_get_contents($composerJson), true, 512, JSON_THROW_ON_ERROR);
		}

		$parentPath = dirname($path);
		if ($parentPath !== $path) {
			return self::getComposerJsonContent($parentPath);
		}

		return [];
	}

	/**
	 * @param class-string $class
	 */
	private static function getMethodOperator(string $class, string $function): string
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
