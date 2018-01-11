<?php

namespace Contributte\Mailing;

use Nette\Mail\IMailer;

class MailSender implements IMailSender
{

	/** @var IMailer */
	private $mailer;

	/**
	 * @param IMailer $mailer
	 */
	public function __construct(IMailer $mailer)
	{
		$this->mailer = $mailer;
	}

	/**
	 * @param MailBuilder $builder
	 * @return void
	 */
	public function send(MailBuilder $builder)
	{
		// Create message
		$message = $builder->getMessage();

		// Create template
		$template = $builder->getTemplate();
		$template->add('_mail', $message);

		// Set template to message
		$message->setHtmlBody($template);

		// Send message
		$this->mailer->send($message);
	}

}
