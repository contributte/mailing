<?php

namespace Contributte\Mailing;

use Nette\Application\LinkGenerator;
use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\Template;

class NetteTemplateFactory extends AbstractTemplateFactory
{

	/** @var ITemplateFactory */
	private $templateFactory;

	/** @var LinkGenerator */
	private $linkGenerator;

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

}
