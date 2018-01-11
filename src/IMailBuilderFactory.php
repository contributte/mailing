<?php

namespace Contributte\Mailing;

interface IMailBuilderFactory
{

	/**
	 * @return MailBuilder
	 */
	public function create();

}
