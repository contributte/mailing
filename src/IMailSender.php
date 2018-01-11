<?php

namespace Contributte\Mailing;

interface IMailSender
{

	/**
	 * @param MailBuilder $builder
	 * @return void
	 */
	public function send(MailBuilder $builder);

}
