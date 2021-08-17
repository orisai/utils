<?php declare(strict_types = 1);

namespace Tests\Orisai\Utils\Unit\Dependencies\Exception;

use Generator;
use Orisai\Utils\Dependencies\Exception\ExtensionRequired;
use PHPUnit\Framework\TestCase;

final class ExtensionRequiredTest extends TestCase
{

	public function testFunction(): void
	{
		$this->expectException(ExtensionRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use function testFunction().
Problem: Required php extension foo is not installed.
MSG);

		throw ExtensionRequired::forFunction(['foo'], __FUNCTION__);
	}

	public function testMethod(): void
	{
		$this->expectException(ExtensionRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use method
         Tests\Orisai\Utils\Unit\Dependencies\Exception\ExtensionRequiredTest->testMethod().
Problem: Required php extension foo is not installed.
MSG);

		throw ExtensionRequired::forMethod(['foo'], self::class, __FUNCTION__);
	}

	public function testClassWithSingular(): void
	{
		$this->expectException(ExtensionRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use class
         Tests\Orisai\Utils\Unit\Dependencies\Exception\ExtensionRequiredTest.
Problem: Required php extension foo is not installed.
MSG);

		throw ExtensionRequired::forClass(['foo'], self::class);
	}

	public function testClassWithPlural(): void
	{
		$this->expectException(ExtensionRequired::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Trying to use class
         Tests\Orisai\Utils\Unit\Dependencies\Exception\ExtensionRequiredTest.
Problem: Required php extensions foo, bar are not installed.
MSG);

		throw ExtensionRequired::forClass(['foo', 'bar'], self::class);
	}

	/**
	 * @dataProvider provideGetter
	 */
	public function testGetter(ExtensionRequired $exception): void
	{
		self::assertSame(
			['foo', 'bar'],
			$exception->getExtensions(),
		);
	}

	/**
	 * @return Generator<array<mixed>>
	 */
	public function provideGetter(): Generator
	{
		$extensions = ['foo', 'bar'];

		yield [
			ExtensionRequired::forClass($extensions, self::class),
		];

		yield [
			ExtensionRequired::forMethod($extensions, self::class, __FUNCTION__),
		];

		yield [
			ExtensionRequired::forFunction($extensions, __FUNCTION__),
		];
	}

}
