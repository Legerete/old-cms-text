<?php

/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @package     Wunderman\CMS\Text
 */

namespace Wunderman\CMS\Text\PublicModule\Components\Text;

use Nette\Application\UI\Control;
use Kdyby\Doctrine\EntityManager;
use Tracy\Debugger;
use Wunderman\CMS\Text\Entity\TextArticle;

class Text extends Control
{

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var TextArticle
	 */
	private $textArticleRepository;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->textArticleRepository = $em->getRepository(TextArticle::class);
	}


	/**
	 * @var array $entity_params
	 */
	public function render($entity_params)
	{
		if (!isset($entity_params['id'])) {
			throw new \InvalidArgumentException('Text id is not set.');
		}

		$this->getTemplate()->article = $this->textArticleRepository->findOneBy([
			'id' => $entity_params['id']
		]);

		$template = isset($entity_params['template']) ? $entity_params['template'] : 'Text.latte';
		
		$this->getTemplate()->render(__DIR__ . "/templates/$template");
	}

}
