<?php declare(strict_types = 1);

namespace Tests\Orisai\Utils\Unit\Tester;

use Orisai\Utils\Tester\DependenciesTester;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
final class DependenciesTesterTest extends TestCase
{

	public function testPackages(): void
	{
		self::assertFalse(DependenciesTester::shouldPackageBeIgnored('foo'));
		self::assertFalse(DependenciesTester::shouldPackageBeIgnored('bar'));
		self::assertFalse(DependenciesTester::shouldPackageBeIgnored('baz'));

		DependenciesTester::addIgnoredPackages(['foo', 'bar']);
		DependenciesTester::addIgnoredPackages(['bar', 'baz']);

		self::assertTrue(DependenciesTester::shouldPackageBeIgnored('bar'));
		self::assertTrue(DependenciesTester::shouldPackageBeIgnored('baz'));
		self::assertTrue(DependenciesTester::shouldPackageBeIgnored('foo'));
	}

	public function testExtensions(): void
	{
		self::assertFalse(DependenciesTester::shouldExtensionBeIgnored('foo'));
		self::assertFalse(DependenciesTester::shouldExtensionBeIgnored('bar'));
		self::assertFalse(DependenciesTester::shouldExtensionBeIgnored('baz'));

		DependenciesTester::addIgnoredExtensions(['foo', 'bar']);
		DependenciesTester::addIgnoredExtensions(['bar', 'baz']);

		self::assertTrue(DependenciesTester::shouldExtensionBeIgnored('bar'));
		self::assertTrue(DependenciesTester::shouldExtensionBeIgnored('baz'));
		self::assertTrue(DependenciesTester::shouldExtensionBeIgnored('foo'));
	}

}
