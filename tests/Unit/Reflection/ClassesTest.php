<?php declare(strict_types = 1);

namespace Tests\Orisai\Utils\Unit\Reflection;

use Exception;
use Generator;
use LogicException;
use Orisai\Exceptions\LogicalException;
use Orisai\Utils\Dependencies\Exception\ExtensionRequired;
use Orisai\Utils\Reflection\Classes;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ClassesTest extends TestCase
{

	/**
	 * @param class-string $class
	 * @param array<class-string> $list
	 *
	 * @dataProvider provideClassList
	 */
	public function testClassList(string $class, array $list): void
	{
		self::assertSame(
			$list,
			Classes::getClassList($class),
		);
	}

	/**
	 * @return Generator<array<mixed>>
	 */
	public function provideClassList(): Generator
	{
		yield [
			Classes::class,
			[
				Classes::class,
			],
		];

		yield [
			ExtensionRequired::class,
			[
				ExtensionRequired::class,
				LogicalException::class,
				LogicException::class,
				// phpcs:disable SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
				Exception::class,
				// phpcs:enable
			],
		];
	}

	public function testClassDir(): void
	{
		self::assertStringEndsWith(
			'src/Reflection',
			Classes::getClassDir(Classes::class),
		);
	}

	/**
	 * @param class-string $class
	 *
	 * @dataProvider provideShortName
	 */
	public function testShortName(string $class, string $short): void
	{
		self::assertSame(
			$short,
			Classes::getShortName($class),
		);
	}

	/**
	 * @return Generator<array<mixed>>
	 */
	public function provideShortName(): Generator
	{
		yield [
			stdClass::class,
			stdClass::class,
		];

		yield [
			Classes::class,
			'Classes',
		];
	}

}
