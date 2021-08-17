<?php declare(strict_types = 1);

namespace Orisai\Utils\Dependencies\Exception;

use Orisai\Exceptions\LogicalException;
use Orisai\Exceptions\Message;
use Orisai\Utils\Reflection\Classes;
use function count;
use function implode;

final class ExtensionRequired extends LogicalException
{

	/** @var array<string> */
	private array $extensions;

	/**
	 * @param array<string> $extensions
	 * @param class-string $class
	 */
	public static function forClass(array $extensions, string $class): self
	{
		return self::create($extensions, "class {$class}");
	}

	/**
	 * @param array<string> $extensions
	 * @param class-string $class
	 */
	public static function forMethod(array $extensions, string $class, string $function): self
	{
		$operator = Classes::getMethodOperator($class, $function);

		return self::create($extensions, "method {$class}{$operator}{$function}()");
	}

	/**
	 * @param array<string> $extensions
	 */
	public static function forFunction(array $extensions, string $function): self
	{
		return self::create($extensions, "function {$function}()");
	}

	/**
	 * @param array<string> $extensions
	 */
	private static function create(array $extensions, string $called): self
	{
		$self = new self();
		$self->extensions = $extensions;

		$extensionsInline = implode(', ', $extensions);
		$required = count($extensions) > 1
			? "s {$extensionsInline} are"
			: " {$extensionsInline} is";

		$message = Message::create()
			->withContext("Trying to use {$called}.")
			->withProblem("Required php extension{$required} not installed.");
		$self->withMessage($message);

		return $self;
	}

	/**
	 * @return array<string>
	 */
	public function getExtensions(): array
	{
		return $this->extensions;
	}

}
