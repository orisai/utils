<?php declare(strict_types = 1);

namespace Orisai\Utils\Tester;

use function array_flip;
use function array_key_exists;
use function array_merge;
use function array_values;

final class DependenciesTester
{

	/** @var array<string, mixed> */
	private static array $ignoredPackages = [];

	/** @var array<string, mixed> */
	private static array $ignoredExtensions = [];

	/**
	 * @param array<string> $packages
	 */
	public static function addIgnoredPackages(array $packages): void
	{
		self::$ignoredPackages = array_merge(
			self::$ignoredPackages,
			array_flip(array_values($packages)),
		);
	}

	public static function shouldPackageBeIgnored(string $package): bool
	{
		return array_key_exists($package, self::$ignoredPackages);
	}

	/**
	 * @param array<string> $extensions
	 */
	public static function addIgnoredExtensions(array $extensions): void
	{
		self::$ignoredExtensions = array_merge(
			self::$ignoredExtensions,
			array_flip(array_values($extensions)),
		);
	}

	public static function shouldExtensionBeIgnored(string $extension): bool
	{
		return array_key_exists($extension, self::$ignoredExtensions);
	}

}
