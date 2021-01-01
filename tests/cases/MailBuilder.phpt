<?php declare(strict_types = 1);

namespace Tests;

/**
 * Test: MailBuilder
 */

use Contributte\Mailing\IMailSender;
use Contributte\Mailing\MailBuilder;
use Contributte\Mailing\NetteMailSender;
use Mockery;
use Nette\IOException;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use Nette\Utils\Strings;
use Ninjify\Nunjuck\Toolkit;
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
		->withArgs(function (Message $message): bool {
			return $message->getBody() === 'Plain text';
		});

	$sender = new NetteMailSender($mailer);
	$builder = new MailBuilder($sender);
	$builder->setFrom('foo@bar.baz');
	$builder->setTemplateFile(__DIR__ . '/../fixtures/mails/empty.latte');
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
		->withArgs(function (Message $message): bool {
			return Strings::contains($message->getHtmlBody(), '<img src="https://github.com/f3l1x.png">');
		});

	$sender = new NetteMailSender($mailer);
	$builder = new MailBuilder($sender);
	$builder->setFrom('foo@bar.baz');
	$builder->setTemplateFile(__DIR__ . '/../fixtures/mails/img-absolute.latte');

	$builder->send();
});

// Template with image (relative URL)
Toolkit::test(function (): void {
	$mailer = Mockery::mock(Mailer::class);
	$mailer->shouldReceive('send')
		->once()
		->withArgs(function (Message $message): bool {
			return Assert::isMatching('<img src="%a%">', $message->getHtmlBody());
		});

	$sender = new NetteMailSender($mailer);
	$builder = new MailBuilder($sender);
	$builder->setFrom('foo@bar.baz');
	$builder->setTemplateFile(__DIR__ . '/../fixtures/mails/img-relative.latte');

	$builder->send();
});

// Template with image (not found)
Toolkit::test(function (): void {
	$mailer = Mockery::mock(Mailer::class);
	$mailer->shouldReceive('send')
		->once()
		->withArgs(function (Message $message): bool {
			return $message->getHtmlBody() === '1';
		});

	$sender = new NetteMailSender($mailer);
	$builder = new MailBuilder($sender);
	$builder->setFrom('foo@bar.baz');
	$builder->setTemplateFile(__DIR__ . '/../fixtures/mails/img-notfound.latte');

	Assert::exception(function () use ($builder): void {
		$builder->send();
	}, IOException::class, 'Unable to read file %a%. Failed to open stream: No such file or directory');
});
