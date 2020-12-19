<?php declare(strict_types = 1);

namespace Tests\Orisai\Utils\Unit\Dependencies;

use Generator;
use Orisai\Utils\Dependencies\Exception\PackageRequired;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Orisai\Utils\Doubles\TestClass;

final class PackageRequiredTest extends TestCase
{

	public function testFunction(): void
	{
		$this->expectException(PackageRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use function Tests\Orisai\Utils\Doubles\testFunction().
Problem: Required package "orisai/coding-standard:^1.0.0" is not installed.
Solution: Install with
          >>>composer require "orisai/coding-standard:^1.0.0
MSG);

		throw PackageRequired::forFunction(['orisai/coding-standard'], 'Tests\Orisai\Utils\Doubles\testFunction');
	}

	public function testMethod(): void
	{
		$this->expectException(PackageRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use method
         Tests\Orisai\Utils\Unit\Dependencies\PackageRequiredTest->testMethod().
Problem: Required package "orisai/coding-standard:^1.0.0" is not installed.
Solution: Install with
          >>>composer require "orisai/coding-standard:^1.0.0"<<<
MSG);

		throw PackageRequired::forMethod(['orisai/coding-standard'], self::class, __FUNCTION__);
	}

	public function testClassWithSingular(): void
	{
		$this->expectException(PackageRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use class
         Tests\Orisai\Utils\Unit\Dependencies\PackageRequiredTest.
Problem: Required package "orisai/coding-standard:^1.0.0" is not installed.
Solution: Install with
          >>>composer require "orisai/coding-standard:^1.0.0"<<<
MSG);

		throw PackageRequired::forClass(['orisai/coding-standard'], self::class);
	}

	public function testClassWithPlural(): void
	{
		$this->expectException(PackageRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use class
         Tests\Orisai\Utils\Unit\Dependencies\PackageRequiredTest.
Problem: Required packages orisai/non-existent, example/package are not
         installed.
Solution: Install with
          >>>composer require orisai/non-existent example/package<<<
MSG);

		throw PackageRequired::forClass(['orisai/non-existent', 'example/package'], self::class);
	}

	public function testInternalClass(): void
	{
		$this->expectException(PackageRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use class ReflectionClass.
Problem: Required package orisai/non-existent is not installed.
Solution: Install with
          >>>composer require orisai/non-existent<<<
MSG);

		throw PackageRequired::forClass(['orisai/non-existent'], ReflectionClass::class);
	}

	public function testUndefinedClass(): void
	{
		$this->expectException(PackageRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use method NonExistent::undefined().
Problem: Required package orisai/non-existent is not installed.
Solution: Install with
          >>>composer require orisai/non-existent<<<
MSG);

		throw PackageRequired::forMethod(['orisai/non-existent'], 'NonExistent', 'undefined');
	}

	public function testUndefinedMethod(): void
	{
		$this->expectException(PackageRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use method Tests\Orisai\Utils\Doubles\TestClass::undefined().
Problem: Required package orisai/non-existent is not installed.
Solution: Install with
          >>>composer require orisai/non-existent<<<
MSG);

		throw PackageRequired::forMethod(['orisai/non-existent'], TestClass::class, 'undefined');
	}

	public function testStaticMethod(): void
	{
		$this->expectException(PackageRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use method
         Tests\Orisai\Utils\Doubles\TestClass::staticMethod().
Problem: Required package orisai/non-existent is not installed.
Solution: Install with
          >>>composer require orisai/non-existent<<<
MSG);

		throw PackageRequired::forMethod(['orisai/non-existent'], TestClass::class, 'staticMethod');
	}

	public function testDynamicMethod(): void
	{
		$this->expectException(PackageRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use method
         Tests\Orisai\Utils\Doubles\TestClass->dynamicMethod().
Problem: Required package orisai/non-existent is not installed.
Solution: Install with
          >>>composer require orisai/non-existent<<<
MSG);

		throw PackageRequired::forMethod(['orisai/non-existent'], TestClass::class, 'dynamicMethod');
	}

	public function testPathOfUndefinedClass(): void
	{
		$this->expectException(PackageRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use class
         Tests\Orisai\Utils\Unit\Dependencies\PackageRequiredTest.
Problem: Required package "orisai/coding-standard:^1.0.0" is not installed.
Solution: Install with
          >>>composer require "orisai/coding-standard:^1.0.0"<<<
MSG);

		throw PackageRequired::forUndefinedClass(['orisai/coding-standard'], self::class, __FILE__);
	}

	public function testInvalidPathOfUndefinedClass(): void
	{
		$this->expectException(PackageRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use class NonExistent.
Problem: Required package orisai/non-existent is not installed.
Solution: Install with
          >>>composer require orisai/non-existent<<<
MSG);

		throw PackageRequired::forUndefinedClass(['orisai/non-existent'], 'NonExistent', 'invalid');
	}

	public function testPackageVersionSpecified(): void
	{
		$this->expectException(PackageRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use class
         Tests\Orisai\Utils\Unit\Dependencies\PackageRequiredTest.
Problem: Required package "orisai/coding-standard:^99.99.99" is not installed.
Solution: Install with
          >>>composer require "orisai/coding-standard:^99.99.99"<<<
MSG);

		throw PackageRequired::forClass(['orisai/coding-standard:^99.99.99'], self::class);
	}

	/**
	 * @dataProvider getterProvider
	 */
	public function testGetter(PackageRequired $exception): void
	{
		self::assertSame(
			['example/foo', 'example/bar'],
			$exception->getPackages(),
		);
	}

	/**
	 * @return Generator<array<mixed>>
	 */
	public function getterProvider(): Generator
	{
		$packages = ['example/foo', 'example/bar'];

		yield [
			PackageRequired::forClass($packages, self::class),
		];

		yield [
			PackageRequired::forMethod($packages, self::class, __FUNCTION__),
		];

		yield [
			PackageRequired::forFunction($packages, __FUNCTION__),
		];
	}

}
