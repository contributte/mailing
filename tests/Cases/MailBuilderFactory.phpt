<?php declare(strict_types = 1);

namespace Tests;

use Contributte\Mailing\IMailSender;
use Contributte\Mailing\MailBuilder;
use Contributte\Mailing\MailBuilderFactory;
use Contributte\Mailing\NetteTemplateFactory;
use Contributte\Tester\Environment;
use Contributte\Tester\Toolkit;
use Latte\Engine;
use Mockery;
use Nette\Application\LinkGenerator;
use Nette\Application\Routers\RouteList;
use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Http\UrlScript;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

Toolkit::test(function (): void {
	$linkGenerator = new LinkGenerator(new RouteList(), new UrlScript());

	$sender = Mockery::mock(IMailSender::class);
	$sender->shouldReceive('send')
		->once()
		->andReturnUsing(function (MailBuilder $builder): void {
			Assert::equal(['foo@bar.baz' => null], $builder->getMessage()->getFrom());

			$message = $builder->getMessage();
			$template = $builder->getTemplate();
			$template->add('_mail', $message);
			$message->setHtmlBody($template->__toString());

			$filename = Environment::getTestDir() . date('Y-m-d H-i-s') . microtime() . '.eml';
			file_put_contents($filename, $message->generateMessage());
			Assert::match('%A%<span class="preheader">Awesome emails.</span>%A%', file_get_contents($filename));
		});

	$latteFactory = Mockery::mock(LatteFactory::class);
	$latteFactory
		->shouldReceive('create')
		->once()
		->andReturn(new Engine());

	$templateFactory = new NetteTemplateFactory(new TemplateFactory($latteFactory), $linkGenerator);
	$factory = new MailBuilderFactory($sender, $templateFactory);

	$builder = $factory->create();
	$builder->setFrom('foo@bar.baz');
	$builder->setTemplateFile(__DIR__ . '/../Fixtures/mails/hello.latte');
	$builder->send();
});
