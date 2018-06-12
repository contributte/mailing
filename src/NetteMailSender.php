<?php declare(strict_types = 1);

namespace Contributte\Mailing;

use Nette\Mail\IMailer;

class NetteMailSender implements IMailSender
{

	/** @var IMailer */
	private $mailer;

	public function __construct(IMailer $mailer)
	{
		$this->mailer = $mailer;
	}

	public function send(MailBuilder $builder): void
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
