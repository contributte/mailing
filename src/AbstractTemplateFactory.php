<?php declare(strict_types = 1);

namespace Contributte\Mailing;

use Contributte\Mailing\Exception\Logical\TemplateException;
use Contributte\Mailing\Utils\Templater;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\Strings;

abstract class AbstractTemplateFactory implements IMailTemplateFactory
{

	/** @var array<string, mixed> */
	protected array $defaults = [
		'layout' => '@default',
	];

	/** @var array<string, mixed> */
	protected array $config = [
		'layout' => '@default',
	];

	/**
	 * @param array<string, mixed> $defaults
	 */
	public function setDefaults(array $defaults): void
	{
		$this->defaults = $defaults;
	}

	public function setDefaultsItem(string $key, mixed $value): void
	{
		$this->defaults[$key] = $value;
	}

	/**
	 * @param array<string, mixed> $config
	 */
	public function setConfig(array $config): void
	{
		$this->config = $config;
	}

	public function setConfigItem(string $key, mixed $value): void
	{
		$this->config[$key] = $value;
	}

	protected function prepare(Template $template): Template
	{
		$template = $this->prepareDefaults($template);
		$template = $this->prepareConfig($template);

		return $template;
	}

	protected function prepareDefaults(Template $template): Template
	{
		// Layout
		if (isset($this->defaults['layout'])) {
			assert(is_string($this->defaults['layout']), 'Layout must be string');
			$this->defaults['layout'] = $this->prepareLayout($this->defaults['layout']);
		}

		// Append defaults to template
		Templater::addParameter($template, '_defaults', (object) $this->defaults);

		return $template;
	}

	protected function prepareConfig(Template $template): Template
	{
		// Layout
		if (isset($this->config['layout'])) {
			assert(is_string($this->config['layout']), 'Layout must be string');
			$this->config['layout'] = $this->prepareLayout($this->config['layout']);
		}

		// Append defaults to template
		Templater::addParameter($template, '_config', (object) $this->config);

		return $template;
	}

	protected function prepareLayout(string $layout): string
	{
		if (str_starts_with($layout, '@')) {
			$layout = __DIR__ . '/../resources/layouts/' . $layout;
		}

		$layout = Strings::replace($layout, '#.latte$#', '') . '.latte';

		if (!file_exists($layout)) {
			throw new TemplateException(sprintf('Layout file "%s" not found', $layout));
		}

		return $layout;
	}

}
