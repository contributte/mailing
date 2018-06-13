<?php declare(strict_types = 1);

namespace Contributte\Mailing;

use Latte\Engine;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Mail\Message;

class MailBuilder
{

	/** @var IMailSender */
	protected $sender;

	/** @var Message */
	protected $message;

	/** @var Template */
	protected $template;

	public function __construct(IMailSender $mailer, ?Message $message = null)
	{
		$this->sender = $mailer;
		$this->message = $message ?: new Message();
	}

	public function getTemplate(): Template
	{
		return $this->template ?: $this->template = new Template(new Engine());
	}

	public function setTemplate(Template $template): void
	{
		$this->template = clone $template;
	}

	/**
	 * @param mixed[] $parameters
	 */
	public function setParameters(array $parameters): self
	{
		$this->getTemplate()->setParameters($parameters);

		return $this;
	}

	public function setTemplateFile(string $file): self
	{
		$this->getTemplate()->setFile($file);

		return $this;
	}

	public function getMessage(): Message
	{
		return $this->message;
	}

	public function addTo(string $email, ?string $name = null): self
	{
		$this->message->addTo($email, $name);

		return $this;
	}

	public function addBcc(string $email, ?string $name = null): self
	{
		$this->message->addBcc($email, $name);

		return $this;
	}

	public function addCc(string $email, ?string $name = null): self
	{
		$this->message->addCc($email, $name);

		return $this;
	}

	public function setSubject(string $subject): self
	{
		$this->message->setSubject($subject);

		return $this;
	}

	public function setFrom(string $from, ?string $fromName = null): self
	{
		$this->message->setFrom($from, $fromName);

		return $this;
	}

	public function call(callable $callback): self
	{
		$callback($this->message, $this->template);

		return $this;
	}

	public function send(): void
	{
		$this->sender->send($this);
	}

}
