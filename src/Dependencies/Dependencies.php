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
			if (InstalledVersions::isInstalled($package)) {
				unset($packages[$key]);
			}
		}

		return $packages;
	}

	/**
	 * @param array<string> $extensions
	 * @return array<string>
	 */
	public static function getNotLoadedExtensions(array $extensions): array
	{
		foreach ($extensions as $key => $extension) {
			if (extension_loaded($extension)) {
				unset($extensions[$key]);
			}
		}

		return $extensions;
	}

}
