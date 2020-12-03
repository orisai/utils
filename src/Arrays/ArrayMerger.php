<?php declare(strict_types = 1);

namespace Orisai\Utils\Arrays;

use function is_array;
use function is_int;

final class ArrayMerger
{

	/**
	 * @param array<mixed> $default
	 * @param array<mixed> $toMerge
	 * @return array<mixed>
	 */
	public static function merge(array $default, array $toMerge): array
	{
		foreach ($toMerge as $key => $val) {
			if (is_int($key)) {
				$default[] = $val;
			} else {
				if (isset($default[$key])) {
					$val = self::mergeInternal($default[$key], $val);
				}

				$default[$key] = $val;
			}
		}

		return $default;
	}

	/**
	 * @param mixed $default
	 * @param mixed $toMerge
	 * @return mixed
	 */
	private static function mergeInternal($default, $toMerge)
	{
		if (is_array($toMerge) && is_array($default)) {
			return self::merge($default, $toMerge);
		}

		if ($toMerge === null && is_array($default)) {
			return $default;
		}

		return $toMerge;
	}

}
