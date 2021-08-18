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
		foreach ($toMerge as $key => $value) {
			if (is_int($key)) {
				$default[] = $value;
			} else {
				if (isset($default[$key])) {
					$defaultValue = $default[$key];

					if (is_array($defaultValue) && is_array($value)) {
						$value = self::merge($defaultValue, $value);
					}
				}

				$default[$key] = $value;
			}
		}

		return $default;
	}

}
