<?php declare(strict_types = 1);

namespace Tests\Orisai\Utils\Unit\Dependencies;

use Orisai\Utils\Dependencies\Dependencies;
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
	}

	public function testExtensions(): void
	{
		self::assertSame(['foo', 'bar'], Dependencies::getNotLoadedExtensions(['foo', 'bar']));
		self::assertSame([], Dependencies::getNotLoadedExtensions(['Core', 'date', 'json']));
	}

}
