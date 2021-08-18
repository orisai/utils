<?php declare(strict_types = 1);

namespace Orisai\Utils\Dependencies;

use function array_flip;
use function array_key_exists;
use function array_keys;

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
		self::$ignoredPackages += array_flip($packages);
	}

	public static function shouldPackageBeIgnored(string $package): bool
	{
		return array_key_exists($package, self::$ignoredPackages);
	}

	/**
	 * @return array<string>
	 */
	public static function getIgnoredPackages(): array
	{
		return array_keys(self::$ignoredPackages);
	}

	/**
	 * @param array<string> $extensions
	 */
	public static function addIgnoredExtensions(array $extensions): void
	{
		self::$ignoredExtensions += array_flip($extensions);
	}

	public static function shouldExtensionBeIgnored(string $extension): bool
	{
		return array_key_exists($extension, self::$ignoredExtensions);
	}

	/**
	 * @return array<string>
	 */
	public static function getIgnoredExtensions(): array
	{
		return array_keys(self::$ignoredExtensions);
	}

}
