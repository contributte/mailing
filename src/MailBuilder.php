<?php declare(strict_types = 1);

namespace Contributte\Mailing;

use Contributte\Mailing\Utils\Templater;
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

	/** @var string|null */
	protected $plain;

	/** @var string|null */
	protected $imagePath;

	public function __construct(IMailSender $mailer, ?Message $message = null)
	{
		$this->sender = $mailer;
		$this->message = $message ?? new Message();
		$this->template = new Template(new Engine());
	}

	public function getTemplate(): Template
	{
		return $this->template;
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
		Templater::setParameters($this->template, $parameters);

		return $this;
	}

	public function setTemplateFile(string $file): self
	{
		$this->getTemplate()->setFile($file);

		return $this;
	}

	public function getImagePath(): ?string
	{
		return $this->imagePath;
	}

	public function setImagePath(string $imagePath): void
	{
		$this->imagePath = $imagePath;
	}

	public function getPlain(): ?string
	{
		return $this->plain;
	}

	public function setPlain(string $plain): void
	{
		$this->plain = $plain;
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

	public function addReplyTo(string $email, ?string $name = null): self
	{
		$this->message->addReplyTo($email, $name);

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
