<?php

namespace Contributte\Mailing;

use Contributte\Mailing\Exception\Logical\TemplateException;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\Strings;

class MailTemplateFactory implements IMailTemplateFactory
{

	/** @var ITemplateFactory */
	private $templateFactory;

	/** @var LinkGenerator */
	private $linkGenerator;

	/** @var array */
	private $defaults = [
		'layout' => '@default',
	];

	/** @var array */
	private $config = [
		'layout' => '@default',
	];

	/**
	 * @param ITemplateFactory $templateFactory
	 * @param LinkGenerator $linkGenerator
	 */
	public function __construct(ITemplateFactory $templateFactory, LinkGenerator $linkGenerator)
	{
		$this->templateFactory = $templateFactory;
		$this->linkGenerator = $linkGenerator;
	}

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
	 * API *********************************************************************
	 */

	/**
	 * @return Template
	 */
	public function create()
	{
		/** @var Template $template */
		$template = $this->templateFactory->createTemplate();

		// For macros {link} {plink}
		$template->getLatte()->addProvider('uiControl', $this->linkGenerator);

		// Prepare template
		$template = $this->prepare($template);

		return $template;
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
