<?php declare(strict_types = 1);

namespace Contributte\Mailing;

use AllowDynamicProperties;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\Arrays;

#[AllowDynamicProperties]
final class MailTemplate extends Template
{

	/**
	 * @param mixed[] $params
	 */
	public function setParameters(array $params): self
	{
		return Arrays::toObject($params, $this);
	}

}
