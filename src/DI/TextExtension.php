<?php

/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @author      Pavel Janda <me@paveljanda.com>
 * @package     Wunderman\CMS\Text
 */

namespace Wunderman\CMS\Text\DI;

use Nette\DI\CompilerExtension;
use Nette\Utils\Arrays;

class TextExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$extensionConfig = $this->loadFromFile(__DIR__ . '/config.neon');
		$this->compiler->parseServices($builder, $extensionConfig, $this->name);

		$builder->parameters = Arrays::mergeTree($builder->parameters,
			Arrays::get($extensionConfig, 'parameters', []));
	}


	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		$builder->getDefinition('privateComposePresenter')->addSetup(
			'addExtensionService',
			['text', $this->prefix('@textServiceService')]
		);

		/**
		 * PublicModule component
		 */
		$builder->getDefinition('publicComposePresenter')->addSetup(
			'setComposeComponentFactory',
			['text', $this->prefix('@publicTextFactory')]
		);

		/**
		 * PrivateModule component
		 */
		$builder->getDefinition('privateComposePresenter')->addSetup(
			'setComposeComponentFactory',
			['text', $this->prefix('@privateTextFactory')]
		);
	}

}
