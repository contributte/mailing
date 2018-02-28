<?php

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

	/**
	 * @param IMailSender $mailer
	 * @param Message|NULL $message
	 */
	public function __construct(IMailSender $mailer, Message $message = NULL)
	{
		$this->sender = $mailer;
		$this->message = $message ?: new Message();
	}

	/**
	 * TEMPLATE API ************************************************************
	 */

	/**
	 * @param Template $template
	 * @return void
	 */
	public function setTemplate(Template $template)
	{
		$this->template = clone $template;
	}

	/**
	 * @return Template
	 */
	public function getTemplate()
	{
		return $this->template ?: $this->template = new Template(new Engine());
	}

	/**
	 * @param array $parameters
	 * @return self
	 */
	public function setParameters(array $parameters)
	{
		$this->getTemplate()->setParameters($parameters);

		return $this;
	}

	/**
	 * @param string $file
	 * @return self
	 */
	public function setTemplateFile($file)
	{
		$this->getTemplate()->setFile($file);

		return $this;
	}

	/**
	 * MESSAGE API *************************************************************
	 */

	/**
	 * @return Message
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @param string $email
	 * @param string $name
	 * @return self
	 */
	public function addTo($email, $name = NULL)
	{
		$this->message->addTo($email, $name);

		return $this;
	}

	/**
	 * @param string $email
	 * @param string $name
	 * @return self
	 */
	public function addBcc($email, $name = NULL)
	{
		$this->message->addBcc($email, $name);

		return $this;
	}

	/**
	 * @param string $email
	 * @param string $name
	 * @return self
	 */
	public function addCc($email, $name = NULL)
	{
		$this->message->addCc($email, $name);

		return $this;
	}

	/**
	 * @param string $subject
	 * @return self
	 */
	public function setSubject($subject)
	{
		$this->message->setSubject($subject);

		return $this;
	}

	/**
	 * @param string $from
	 * @param string $fromName
	 * @return self
	 */
	public function setFrom($from, $fromName = NULL)
	{
		$this->message->setFrom($from, $fromName);

		return $this;
	}

	/**
	 * @param callable $callback
	 * @return self
	 */
	public function call(callable $callback)
	{
		$callback($this->message, $this->template);

		return $this;
	}

	/**
	 * SENDER API **************************************************************
	 */

	/**
	 * Build and send message.
	 *
	 * @return void
	 */
	public function send()
	{
		$this->sender->send($this);
	}

}
