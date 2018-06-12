<?php declare(strict_types = 1);

namespace Contributte\Mailing\DI;

use Contributte\Mailing\IMailBuilderFactory;
use Contributte\Mailing\IMailSender;
use Contributte\Mailing\IMailTemplateFactory;
use Contributte\Mailing\MailBuilderFactory;
use Contributte\Mailing\NetteMailSender;
use Contributte\Mailing\NetteTemplateFactory;
use Nette\DI\CompilerExtension;

class MailingExtension extends CompilerExtension
{

	/** @var mixed[] */
	private $defaults = [
		'template' => [
			'defaults' => [
				'layout' => '@@default',
			],
			'config' => [
				'layout' => '@@default',
			],
		],
	];

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		$builder->addDefinition($this->prefix('builderFactory'))
			->setType(IMailBuilderFactory::class)
			->setFactory(MailBuilderFactory::class);

		$builder->addDefinition($this->prefix('sender'))
			->setType(IMailSender::class)
			->setFactory(NetteMailSender::class);

		$templateFactory = $builder->addDefinition($this->prefix('templateFactory'))
			->setType(IMailTemplateFactory::class)
			->setFactory(NetteTemplateFactory::class);

		if ($config['template']['defaults']) {
			$templateFactory->addSetup('setDefaults', [$config['template']['defaults']]);
		}

		if ($config['template']['config']) {
			$templateFactory->addSetup('setConfig', [$config['template']['config']]);
		}
	}

}
