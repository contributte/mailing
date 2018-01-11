<?php

namespace Contributte\Mailing;

use Nette\Bridges\ApplicationLatte\Template;

interface IMailTemplateFactory
{

	/**
	 * @return Template
	 */
	public function create();

}
