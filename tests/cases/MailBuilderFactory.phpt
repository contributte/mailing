<?php

namespace Tests;

/**
 * Test: MailBuilderFactory
 */

use Contributte\Mailing\IMailSender;
use Contributte\Mailing\MailBuilder;
use Contributte\Mailing\MailBuilderFactory;
use Contributte\Mailing\NetteTemplateFactory;
use Latte\Engine;
use Mockery;
use Nette\Application\LinkGenerator;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

test(function () {
	$linkGenerator = Mockery::mock(LinkGenerator::class);

	$sender = Mockery::mock(IMailSender::class);
	$sender->shouldReceive('send')
		->once()
		->andReturnUsing(function (MailBuilder $builder) {
			Assert::equal(['foo@bar.baz' => NULL], $builder->getMessage()->getFrom());

			$message = $builder->getMessage();
			$template = $builder->getTemplate();
			$template->add('_mail', $message);
			$message->setHtmlBody($template);

			$filename = TEMP_DIR . date('Y-m-d H-i-s') . microtime() . '.eml';
			file_put_contents($filename, $message->generateMessage());
			Assert::match('%A%<span class="preheader">Awesome emails.</span>%A%', file_get_contents($filename));
		});

	$latteFactory = Mockery::mock(ILatteFactory::class);
	$latteFactory
		->shouldReceive('create')
		->once()
		->andReturn(new Engine());

	$templateFactory = new NetteTemplateFactory(new TemplateFactory($latteFactory), $linkGenerator);
	$factory = new MailBuilderFactory($sender, $templateFactory);

	$builder = $factory->create();
	$builder->setFrom('foo@bar.baz');
	$builder->setTemplateFile(__DIR__ . '/../fixtures/mails/hello.latte');
	$builder->send();
});
