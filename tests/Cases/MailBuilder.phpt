<?php declare(strict_types = 1);

namespace Tests;

use Contributte\Mailing\IMailSender;
use Contributte\Mailing\MailBuilder;
use Contributte\Mailing\NetteMailSender;
use Contributte\Tester\Toolkit;
use Mockery;
use Nette\IOException;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use Nette\Utils\Strings;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

Toolkit::test(function (): void {
	$sender = Mockery::mock(IMailSender::class);
	$builder = new MailBuilder($sender);
	$builder->setFrom('foo@bar.baz');

	Assert::equal(['foo@bar.baz' => null], $builder->getMessage()->getFrom());
});

Toolkit::test(function (): void {
	$mailer = Mockery::mock(Mailer::class);
	$mailer->shouldReceive('send')
		->once()
		->withArgs(fn (Message $message): bool => $message->getBody() === 'Plain text');

	$sender = new NetteMailSender($mailer);
	$builder = new MailBuilder($sender);
	$builder->setFrom('foo@bar.baz');
	$builder->setTemplateFile(__DIR__ . '/../Fixtures/mails/empty.latte');
	$builder->setPlain('Plain text');

	$builder->send();
});

Toolkit::test(function (): void {
	$sender = Mockery::mock(IMailSender::class);
	$builder = new MailBuilder($sender);
	$t1 = $builder->getTemplate();
	$t2 = $builder->getTemplate();

	Assert::same($t1, $t2);
});

// Template with image (absolute URL)
Toolkit::test(function (): void {
	$mailer = Mockery::mock(Mailer::class);
	$mailer->shouldReceive('send')
		->once()
		->withArgs(fn (Message $message): bool => Strings::contains($message->getHtmlBody(), '<img src="https://github.com/f3l1x.png">'));

	$sender = new NetteMailSender($mailer);
	$builder = new MailBuilder($sender);
	$builder->setFrom('foo@bar.baz');
	$builder->setTemplateFile(__DIR__ . '/../Fixtures/mails/img-absolute.latte');

	$builder->send();
});

// Template with image (relative URL)
Toolkit::test(function (): void {
	$mailer = Mockery::mock(Mailer::class);
	$mailer->shouldReceive('send')
		->once()
		->withArgs(fn (Message $message): bool => Assert::isMatching('<img src="%a%">', $message->getHtmlBody()));

	$sender = new NetteMailSender($mailer);
	$builder = new MailBuilder($sender);
	$builder->setFrom('foo@bar.baz');
	$builder->setTemplateFile(__DIR__ . '/../Fixtures/mails/img-relative.latte');

	$builder->send();
});

// Template with image (not found)
Toolkit::test(function (): void {
	$mailer = Mockery::mock(Mailer::class);
	$mailer->shouldReceive('send')
		->once()
		->withArgs(fn (Message $message): bool => $message->getHtmlBody() === '1');

	$sender = new NetteMailSender($mailer);
	$builder = new MailBuilder($sender);
	$builder->setFrom('foo@bar.baz');
	$builder->setTemplateFile(__DIR__ . '/../Fixtures/mails/img-notfound.latte');

	Assert::exception(function () use ($builder): void {
		$builder->send();
	}, IOException::class, 'Unable to read file %a%. %a% to open stream: No such file or directory');
});
