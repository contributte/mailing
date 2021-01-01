<?php declare(strict_types = 1);

namespace Contributte\Mailing\Utils;

use Contributte\Mailing\Exception\RuntimeException;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\Arrays;

class Templater
{

	/**
	 * @param mixed $value
	 */
	public static function addParameter(Template $template, string $name, $value): Template
	{
		if (property_exists($template, $name)) {
			throw new RuntimeException("The variable '$name' already exists.");
		}

		$template->$name = $value;

		return $template;
	}

	/**
	 * @param mixed[] $parameters
	 */
	public static function addParameters(Template $template, array $parameters): Template
	{
		foreach ($parameters as $key => $value) {
			self::addParameter($template, $key, $value);
		}

		return $template;
	}

	/**
	 * @param mixed $value
	 */
	public static function setParameter(Template $template, string $name, $value): Template
	{
		Arrays::toObject([$name => $value], $template);

		return $template;
	}

	/**
	 * @param mixed[] $parameters
	 */
	public static function setParameters(Template $template, array $parameters): Template
	{
		Arrays::toObject($parameters, $template);

		return $template;
	}

}
