<?php

/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @package     Wunderman\CMS\Text
 */

namespace Wunderman\CMS\Text\PrivateModule\Components\Text;

use Kdyby\Doctrine\EntityManager;
use Wunderman\CMS\Text\PublicModule;

class Text extends PublicModule\Components\Text\Text
{

	/**
	 * @var array
	 */
	protected $componentParams;


	public function __construct(array $componentParams = [], EntityManager $em)
	{
		parent::__construct($em);

		$this->componentParams = $componentParams;
	}


	/**
	 * @var int $id
	 */
	public function render($id = null)
	{
		$params = [];

		foreach ($this->componentParams as $param)
		{
			$params[$param->name] = $param->value;
		}

		parent::render($params);
	}

}
