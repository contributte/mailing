<?php declare(strict_types = 1);

namespace Contributte\Mailing;

class MailBuilderFactory implements IMailBuilderFactory
{

	/** @var IMailSender */
	protected $sender;

	/** @var IMailTemplateFactory */
	protected $templateFactory;

	public function __construct(IMailSender $sender, IMailTemplateFactory $templateFactory)
	{
		$this->sender = $sender;
		$this->templateFactory = $templateFactory;
	}

	public function create(): MailBuilder
	{
		$mail = new MailBuilder($this->sender);
		$mail->setTemplate($this->templateFactory->create());

		return $mail;
	}

}
