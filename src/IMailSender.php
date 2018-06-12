<?php declare(strict_types = 1);

namespace Contributte\Mailing;

interface IMailSender
{

	public function send(MailBuilder $builder): void;

}
