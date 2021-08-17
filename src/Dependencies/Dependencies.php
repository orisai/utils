<?php declare(strict_types = 1);

namespace Orisai\Utils\Dependencies;

use Composer\InstalledVersions;
use function extension_loaded;

final class Dependencies
{

	/**
	 * @param array<string> $packages
	 * @return array<string>
	 */
	public static function getNotLoadedPackages(array $packages): array
	{
		foreach ($packages as $key => $package) {
			if (self::isPackageLoaded($package)) {
				unset($packages[$key]);
			}
		}

		return $packages;
	}

	public static function isPackageLoaded(string $package): bool
	{
		return InstalledVersions::isInstalled($package) && !DependenciesTester::shouldPackageBeIgnored($package);
	}

	/**
	 * @param array<string> $extensions
	 * @return array<string>
	 */
	public static function getNotLoadedExtensions(array $extensions): array
	{
		foreach ($extensions as $key => $extension) {
			if (self::isExtensionLoaded($extension)) {
				unset($extensions[$key]);
			}
		}

		return $extensions;
	}

	public static function isExtensionLoaded(string $extension): bool
	{
		return extension_loaded($extension) && !DependenciesTester::shouldExtensionBeIgnored($extension);
	}

}
