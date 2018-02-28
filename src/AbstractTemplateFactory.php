<?php

namespace Contributte\Mailing;

use Contributte\Mailing\Exception\Logical\TemplateException;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\Strings;

abstract class AbstractTemplateFactory implements IMailTemplateFactory
{

	/** @var array */
	protected $defaults = [
		'layout' => '@default',
	];

	/** @var array */
	protected $config = [
		'layout' => '@default',
	];

	/**
	 * GETTERS/SETTERS *********************************************************
	 */

	/**
	 * @param array $defaults
	 * @return void
	 */
	public function setDefaults(array $defaults)
	{
		$this->defaults = $defaults;
	}

	/**
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
	public function setDefaultsItem($key, $value)
	{
		$this->defaults[$key] = $value;
	}

	/**
	 * @param array $config
	 * @return void
	 */
	public function setConfig(array $config)
	{
		$this->config = $config;
	}

	/**
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
	public function setConfigItem($key, $value)
	{
		$this->config[$key] = $value;
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @param Template $template
	 * @return Template
	 */
	protected function prepare(Template $template)
	{
		$template = $this->prepareDefaults($template);
		$template = $this->prepareConfig($template);

		return $template;
	}

	/**
	 * @param Template $template
	 * @return Template
	 */
	protected function prepareDefaults(Template $template)
	{
		// Layout
		if ($this->defaults['layout']) {
			$this->defaults['layout'] = $this->prepareLayout($this->defaults['layout']);
		}

		// Append defaults to template
		$template->add('_defaults', (object) $this->defaults);

		return $template;
	}

	/**
	 * @param Template $template
	 * @return Template
	 */
	protected function prepareConfig(Template $template)
	{
		// Layout
		if ($this->config['layout']) {
			$this->config['layout'] = $this->prepareLayout($this->config['layout']);
		}

		// Append defaults to template
		$template->add('_config', (object) $this->config);

		return $template;
	}

	/**
	 * @param string $layout
	 * @return string
	 */
	protected function prepareLayout($layout)
	{
		if (Strings::startsWith($layout, '@')) {
			$layout = __DIR__ . '/../resources/layouts/' . $layout;
		}

		$layout = Strings::replace($layout, '#.latte$#', '') . '.latte';

		if (!file_exists($layout)) {
			throw new TemplateException(sprintf('Layout file "%s" not found', $layout));
		}

		return $layout;
	}

}
