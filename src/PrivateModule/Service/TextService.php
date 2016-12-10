<?php

/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @package     Wunderman\CMS\Text
 */

namespace Wunderman\CMS\Text\PrivateModule\Service;

use App\PrivateModule\PagesModule\Presenter\IExtensionService;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Form;
use Nette\Http\Request;
use Nette\Utils\ArrayHash;
use Nette\Utils\Finder;
use Nette\Utils\Strings;
use Symfony\Component\Finder\SplFileInfo;
use Tracy\Debugger;
use Wunderman\CMS\Text\Entity\TextArticle;

class TextService implements IExtensionService
{

	/**
	 * @var Request
	 */
	private $httpRequest;

	/**
	 * @type EntityManager
	 */
	private $em;

	/**
	 * TextService constructor.
	 *
	 * @param Request $httpRequest
	 * @param EntityManager $em
	 */
	public function __construct(Request $httpRequest, EntityManager $em)
	{
		$this->httpRequest = $httpRequest;
		$this->em = $em;
	}

	/**
	 * Prepare adding new item, add imputs to global form etc.
	 *
	 * @param Form $button
	 *
	 * @return mixed
	 */
	public function addItem(Form $form)
	{
		if(isset($form[self::ITEM_CONTAINER])) {
			unset($form[self::ITEM_CONTAINER]);
		}
		$item = $form->addContainer(self::ITEM_CONTAINER);
		$item->addHidden('itemType')->setValue('text');
		$item->addText('title', 'Title');
		$item->addTextArea('content', 'Content');
		$item->addSelect('template', 'Template', $this->getAvailableTextTemplates());
		$item->addHidden('type')->setValue('text');
		$item->addHidden('itemId');
	}

	/**
	 * @param Form $form
	 *
	 * @return mixed
	 */
	public function editItemParams(Form $form, $editItem)
	{
		$params = $this->createParamsAssocArray($editItem->params);
		$text = $this->textArticleRepository()->find((int) $params['id']);
		$this->addItem($form);
		$form[self::ITEM_CONTAINER]->setDefaults([
			'itemId' => $editItem->id,
			'title' => $text->title,
			'template' => isset($params['template']) ? $params['template'] : null,
			'content' => $text->content,
		]);
	}

	/**
	 * Make magic for creating new item, e.g. save new image and return his params for save.
	 * @var array $values Form values
	 * @return array Associated array in pair [ propertyName => value ] for store to the database
	 */
	public function processNew(Form $form, ArrayHash $values)
	{
		$text = new TextArticle();
		$text->setTitle($values['title'])
			->setContent($values['content']);
		$this->em->persist($text)->flush();
		return [
			'id' => $text->getId(),
			'template' => $values->template
		];
	}

	/**
	 * Editing current edited item
	 * @var array $values Form values
	 * @var array $itemParams
	 * @return array
	 */
	public function processEdit(Form $form, ArrayHash $values, $itemParams)
	{
		$text = $this->textArticleRepository()->find($itemParams['id']);
		$text->setTitle($values['title'])
			->setContent($values['content']);
		return [
			'template' => $values['template'],
		];
	}

	/**
	 * Compute anchor for item on the page
	 * @var object
	 * @return string
	 */
	public function getAnchor($item)
	{
		$params = $this->createParamsAssocArray($item->params);
		$text = $this->textArticleRepository()->find((int) $params['id']);
		return $text ? \Nette\Utils\Strings::webalize($text->title) : false;
	}

	/**
	 * @return string
	 */
	public function getAddItemTemplate()
	{
		return realpath(__DIR__ . '/../Templates/editItem.latte');
	}

	/**
	 * @return string
	 */
	public function getEditItemTemplate()
	{
		return $this->getAddItemTemplate();
	}

	/**
	 * @param $params
	 *
	 * @return array
	 */
	private function createParamsAssocArray($params)
	{
		$assocParams = [];
		foreach ($params as $param) {
			$assocParams[$param->name] = $param->value;
		}
		return $assocParams;
	}

	/**
	 * @return array
	 */
	private function getAvailableTextTemplates()
	{
		/** @var Finder $templates */
		$templates = \Nette\Utils\Finder::findFiles('*.latte')->from(realpath(__DIR__.'/../../PublicModule/Components/Text/templates/'));

		$availableTemplates = [];
		/** @var SplFileInfo $template */
		foreach ($templates as $template)
		{
			$templateWithExtension = $template->getFilename();
			$templateName = $template->getBasename('.'.$template->getExtension());
			$templateName = preg_split('/(?=[A-Z])/', $templateName, -1, PREG_SPLIT_NO_EMPTY);
			array_walk($templateName, function(&$item){
				$item = strtolower($item);
			});
			$templateName = implode(' ', $templateName);
			$availableTemplates[$templateWithExtension] = $templateName;
		}

		return $availableTemplates;
	}

	/**
	 * @return \Kdyby\Doctrine\EntityRepository
	 */
	public function textArticleRepository()
	{
		return $this->em->getRepository(TextArticle::class);
	}
}
