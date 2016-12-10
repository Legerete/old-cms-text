<?php

/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @author      Pavel Janda <me@paveljanda.com>
 * @package     Wunderman\CMS\Text
 */

namespace Wunderman\CMS\Text\PublicModule\Components\Text;

interface ITextFactory
{

	/**
	 * @return Text
	 */
	public function create();

}
