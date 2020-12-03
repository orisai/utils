<?php declare(strict_types = 1);

namespace Tests\Orisai\Utils\Unit\Arrays;

use Generator;
use Orisai\Utils\Arrays\ArrayMerger;
use PHPUnit\Framework\TestCase;

final class ArrayMergerTest extends TestCase
{

	/**
	 * @param array<mixed> $default
	 * @param array<mixed> $toMerge
	 * @param array<mixed> $expected
	 * @dataProvider provideTest
	 */
	public function test(array $default, array $toMerge, array $expected): void
	{
		self::assertSame($expected, ArrayMerger::merge($default, $toMerge));
	}

	/**
	 * @return Generator<array<array<mixed>>>
	 */
	public function provideTest(): Generator
	{
		yield 'multiple levels' => [
			[
				'default' => 'default',
				'overridden' => 'not-overridden',
				'merged' => [
					'foo' => 'not-overridden',
					'bar' => 'not-overridden',
					'baz' => 'not-overridden',
				],
			],
			[
				'overridden' => 'overridden',
				'new' => 'new',
				'merged' => [
					'baz' => 'overridden',
				],
			],
			[
				'default' => 'default',
				'overridden' => 'overridden',
				'merged' => [
					'foo' => 'not-overridden',
					'bar' => 'not-overridden',
					'baz' => 'overridden',
				],
				'new' => 'new',
			],
		];

		yield '(non-)numeric keys' => [
			[
				2 => 'two merged',
				3 => 'three merged',
				4 => 'four merged',
			],
			[
				0 => 'zero default',
				'one' => 'one default',
				2 => 'two default',
				3 => 'three default',
			],
			[
				2 => 'two merged',
				3 => 'three merged',
				4 => 'four merged',
				5 => 'zero default',
				'one' => 'one default',
				6 => 'two default',
				7 => 'three default',
			],
		];
	}

}
