<?php declare(strict_types = 1);

namespace Tests;

/**
 * Test: MailBuilder
 */

use Contributte\Mailing\IMailSender;
use Contributte\Mailing\MailBuilder;
use Contributte\Mailing\NetteMailSender;
use Mockery;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

test(function (): void {
	$sender = Mockery::mock(IMailSender::class);
	$builder = new MailBuilder($sender);
	$builder->setFrom('foo@bar.baz');

	Assert::equal(['foo@bar.baz' => null], $builder->getMessage()->getFrom());
});

test(function (): void {
	$mailer = Mockery::mock(IMailer::class);
	$mailer->shouldReceive('send')
		->once()
		->withArgs(function (Message $message): bool {
			return $message->getBody() === 'Plain text';
		});

	$sender = new NetteMailSender($mailer);
	$builder = new MailBuilder($sender);
	$builder->setFrom('foo@bar.baz');
	$builder->getTemplate()->setFile(__DIR__ . '/../fixtures/mails/empty.latte');
	$builder->setPlain('Plain text');

	$builder->send();
});
