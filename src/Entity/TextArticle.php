<?php

namespace Wunderman\CMS\Text\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Petr Besir Horáček <sirbesir@gmail.com>
 * @ORM\Entity
 * @ORM\Table(name="text_article")
 */
class TextArticle extends \Kdyby\Doctrine\Entities\BaseEntity
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;
	/**
	 * @ORM\Column(type="string")
	 */
	protected $title;
	/**
	 * @ORM\Column(type="string")
	 */
	protected $keywords;
	/**
	 * @ORM\Column(type="string")
	 */
	protected $description;
	/**
	 * @ORM\Column(type="string")
	 */
	protected $content;
	/**
	 * @ORM\Column(type="string")
	 */
	protected $notes;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $category;
	/**
	 * @ORM\Column(type="enum")
	 */
	protected $status = 'ok';
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $homepage = 0;
}