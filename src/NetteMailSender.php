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

		// Set folder for embedded images
		$imageFolder = null;
		if (is_string($template->getFile())) {
			$imageFolder = dirname($template->getFile());
		}

		// Set template to message
		$message->setHtmlBody(
			$template->renderToString($template->getFile(), $template->getParameters()),
			$imageFolder
		);

		// Set plaintext to message (if any)
		if ($builder->getPlain() !== null) {
			$message->setBody($builder->getPlain());
		}

		// Send message
		$this->mailer->send($message);
	}

}
