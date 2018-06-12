<?php declare(strict_types = 1);

namespace Contributte\Mailing;

interface IMailBuilderFactory
{

	public function create(): MailBuilder;

}
