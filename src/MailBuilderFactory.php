<?php

namespace Contributte\Mailing;

class MailBuilderFactory implements IMailBuilderFactory
{

	/** @var IMailSender */
	protected $sender;

	/** @var IMailTemplateFactory */
	protected $templateFactory;

	/**
	 * @param IMailSender $sender
	 * @param IMailTemplateFactory $templateFactory
	 */
	public function __construct(IMailSender $sender, IMailTemplateFactory $templateFactory)
	{
		$this->sender = $sender;
		$this->templateFactory = $templateFactory;
	}

	/**
	 * @return MailBuilder
	 */
	public function create()
	{
		$mail = new MailBuilder($this->sender);
		$mail->setTemplate($this->templateFactory->create());

		return $mail;
	}

}
