<?php declare(strict_types = 1);

namespace Tests\Orisai\Utils\Unit\Dependencies;

use Orisai\Utils\Dependencies\DependenciesTester;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
final class DependenciesTesterTest extends TestCase
{

	public function testPackages(): void
	{
		self::assertSame([], DependenciesTester::getIgnoredPackages());
		self::assertFalse(DependenciesTester::shouldPackageBeIgnored('foo'));
		self::assertFalse(DependenciesTester::shouldPackageBeIgnored('bar'));
		self::assertFalse(DependenciesTester::shouldPackageBeIgnored('baz'));

		DependenciesTester::addIgnoredPackages(['foo', 'bar']);
		DependenciesTester::addIgnoredPackages(['bar', 'baz']);

		self::assertTrue(DependenciesTester::shouldPackageBeIgnored('bar'));
		self::assertTrue(DependenciesTester::shouldPackageBeIgnored('baz'));
		self::assertTrue(DependenciesTester::shouldPackageBeIgnored('foo'));
		self::assertSame(['foo', 'bar', 'baz'], DependenciesTester::getIgnoredPackages());
	}

	public function testExtensions(): void
	{
		self::assertSame([], DependenciesTester::getIgnoredExtensions());
		self::assertFalse(DependenciesTester::shouldExtensionBeIgnored('foo'));
		self::assertFalse(DependenciesTester::shouldExtensionBeIgnored('bar'));
		self::assertFalse(DependenciesTester::shouldExtensionBeIgnored('baz'));

		DependenciesTester::addIgnoredExtensions(['foo', 'bar']);
		DependenciesTester::addIgnoredExtensions(['bar', 'baz']);

		self::assertTrue(DependenciesTester::shouldExtensionBeIgnored('bar'));
		self::assertTrue(DependenciesTester::shouldExtensionBeIgnored('baz'));
		self::assertTrue(DependenciesTester::shouldExtensionBeIgnored('foo'));
		self::assertSame(['foo', 'bar', 'baz'], DependenciesTester::getIgnoredExtensions());
	}

}
