<?php declare(strict_types = 1);

namespace Contributte\Mailing\DI;

use Contributte\Mailing\IMailBuilderFactory;
use Contributte\Mailing\IMailSender;
use Contributte\Mailing\IMailTemplateFactory;
use Contributte\Mailing\MailBuilderFactory;
use Contributte\Mailing\NetteMailSender;
use Contributte\Mailing\NetteTemplateFactory;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;

/**
 * @property-read stdClass $config
 */
class MailingExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'template' => Expect::structure([
				'defaults' => Expect::arrayOf('mixed')->default(['layout' => '@@default']),
				'config' => Expect::arrayOf('mixed')->default(['layout' => '@@default']),
			]),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		$builder->addDefinition($this->prefix('builderFactory'))
			->setType(IMailBuilderFactory::class)
			->setFactory(MailBuilderFactory::class);

		$builder->addDefinition($this->prefix('sender'))
			->setType(IMailSender::class)
			->setFactory(NetteMailSender::class);

		$templateFactory = $builder->addDefinition($this->prefix('templateFactory'))
			->setType(IMailTemplateFactory::class)
			->setFactory(NetteTemplateFactory::class);

		if ($config->template->defaults) {
			$templateFactory->addSetup('setDefaults', [$config->template->defaults]);
		}

		if ($config->template->config) {
			$templateFactory->addSetup('setConfig', [$config->template->config]);
		}
	}

}
