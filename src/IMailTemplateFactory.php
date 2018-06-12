<?php declare(strict_types = 1);

namespace Contributte\Mailing;

use Nette\Bridges\ApplicationLatte\Template;

interface IMailTemplateFactory
{

	public function create(): Template;

}
