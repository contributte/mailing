<?php declare(strict_types = 1);

namespace Contributte\Mailing;

class MailBuilderFactory implements IMailBuilderFactory
{
	protected ?string $defaultSender = null;

	/**
	 * @param array{defaultSender?: string|null} $params
	 */
	public function __construct(
		protected array $params,
		protected IMailSender $sender,
		protected IMailTemplateFactory $templateFactory,
	) {
		$this->defaultSender = $params['defaultSender'] ?? null;
	}

	public function create(): MailBuilder
	{
		$mail = new MailBuilder($this->sender);
		$mail->setTemplate($this->templateFactory->create());

		if ($this->defaultSender !== null) {
			$mail->setFrom($this->defaultSender);
		}

		return $mail;
	}

}
