<?php

namespace Contributte\Mailing\DI;

use Contributte\Mailing\IMailBuilderFactory;
use Contributte\Mailing\IMailSender;
use Contributte\Mailing\IMailTemplateFactory;
use Contributte\Mailing\MailBuilderFactory;
use Contributte\Mailing\MailSender;
use Contributte\Mailing\MailTemplateFactory;
use Nette\DI\CompilerExtension;

class MailingExtension extends CompilerExtension
{

	/** @var array */
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

	/**
	 * Register services
	 *
	 * @return void
	 */
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		$builder->addDefinition($this->prefix('builderFactory'))
			->setType(IMailBuilderFactory::class)
			->setFactory(MailBuilderFactory::class);

		$builder->addDefinition($this->prefix('sender'))
			->setType(IMailSender::class)
			->setFactory(MailSender::class);

		$templateFactory = $builder->addDefinition($this->prefix('templateFactory'))
			->setType(IMailTemplateFactory::class)
			->setFactory(MailTemplateFactory::class);

		if ($config['template']['defaults']) {
			$templateFactory->addSetup('setDefaults', [$config['template']['defaults']]);
		}

		if ($config['template']['config']) {
			$templateFactory->addSetup('setConfig', [$config['template']['config']]);
		}
	}

}
