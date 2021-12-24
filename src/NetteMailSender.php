<?php declare(strict_types = 1);

namespace Contributte\Mailing;

use Contributte\Mailing\Utils\Templater;
use Nette\Mail\Mailer;

class NetteMailSender implements IMailSender
{

	/** @var Mailer */
	private $mailer;

	public function __construct(Mailer $mailer)
	{
		$this->mailer = $mailer;
	}

	public function send(MailBuilder $builder): void
	{
		// Create message
		$message = $builder->getMessage();

		// Create template
		$template = $builder->getTemplate();
		Templater::addParameter($template, '_mail', $message);

		// Set template to message
		$message->setHtmlBody(
			$template->__toString(),
			$builder->getImagePath() ?? (is_string($template->getFile()) ? dirname($template->getFile()) : null)
		);

		// Set plaintext to message (if any)
		if ($builder->getPlain() !== null) {
			$message->setBody($builder->getPlain());
		}

		// Send message
		$this->mailer->send($message);
	}

}
