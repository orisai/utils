<?php declare(strict_types = 1);

namespace Tests\Orisai\Utils\Unit\Dependencies;

use Orisai\Utils\Dependencies\Dependencies;
use Orisai\Utils\Dependencies\DependenciesTester;
use PHPUnit\Framework\TestCase;

final class DependenciesTest extends TestCase
{

	public function testPackages(): void
	{
		self::assertSame(
			['orisai/non-existent', 'example/package'],
			Dependencies::getNotLoadedPackages(['orisai/non-existent', 'example/package']),
		);
		self::assertSame([], Dependencies::getNotLoadedPackages(['orisai/exceptions', 'orisai/coding-standard']));

		self::assertTrue(Dependencies::isPackageLoaded('orisai/exceptions'));
		self::assertTrue(Dependencies::isPackageLoaded('orisai/coding-standard'));
		self::assertFalse(Dependencies::isPackageLoaded('orisai/non-existent'));
		self::assertFalse(Dependencies::isPackageLoaded('example/package'));
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testPackagesIgnored(): void
	{
		self::assertSame([], Dependencies::getNotLoadedPackages(['orisai/exceptions']));
		self::assertTrue(Dependencies::isPackageLoaded('orisai/exceptions'));

		DependenciesTester::addIgnoredPackages(['orisai/exceptions']);

		self::assertSame(['orisai/exceptions'], Dependencies::getNotLoadedPackages(['orisai/exceptions']));
		self::assertFalse(Dependencies::isPackageLoaded('orisai/exceptions'));
	}

	public function testExtensions(): void
	{
		self::assertSame(['foo', 'bar'], Dependencies::getNotLoadedExtensions(['foo', 'bar']));
		self::assertSame([], Dependencies::getNotLoadedExtensions(['Core', 'date', 'json']));

		self::assertTrue(Dependencies::isExtensionLoaded('Core'));
		self::assertTrue(Dependencies::isExtensionLoaded('date'));
		self::assertFalse(Dependencies::isExtensionLoaded('foo'));
		self::assertFalse(Dependencies::isExtensionLoaded('bar'));

	}

	/**
	 * @runInSeparateProcess
	 */
	public function testExtensionsIgnored(): void
	{
		self::assertSame([], Dependencies::getNotLoadedExtensions(['Core']));
		self::assertTrue(Dependencies::isExtensionLoaded('Core'));

		DependenciesTester::addIgnoredExtensions(['Core']);

		self::assertSame(['Core'], Dependencies::getNotLoadedExtensions(['Core']));
		self::assertFalse(Dependencies::isExtensionLoaded('Core'));
	}

}
