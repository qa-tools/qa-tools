<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\HtmlElements;


use aik099\QATools\HtmlElements\Element\INamed;
use aik099\QATools\HtmlElements\Element\TypifiedElement;
use aik099\QATools\PageObject\ElementLocator\IElementLocator;
use aik099\QATools\PageObject\Element\WebElement;
use aik099\QATools\PageObject\Exception\ElementNotFoundException;
use aik099\QATools\PageObject\IPageFactory;
use aik099\QATools\PageObject\WebElementProxy;

/**
 * Class for lazy-proxy creation to ensure, that TypifiedElements are
 * really accessed only at moment, when user needs them.
 *
 * @link http://bit.ly/14TbcR9
 */
class TypifiedElementProxy extends WebElementProxy implements INamed
{

	/**
	 * Name of the element.
	 *
	 * @var string
	 */
	private $_name;

	/**
	 * Class name to wrap inside the typified element.
	 *
	 * @var string
	 */
	protected $wrappedClassName = '\\aik099\\QATools\\PageObject\\Element\\WebElement';

	/**
	 * Initializes proxy for TypifiedElement.
	 *
	 * @param IElementLocator $locator      Element selector.
	 * @param IPageFactory    $page_factory Page factory.
	 * @param string          $name         Name of the element.
	 */
	public function __construct(IElementLocator $locator, IPageFactory $page_factory, $name)
	{
		$this->_name = $name;
		$this->className = '\\aik099\\QATools\\HtmlElements\\Element\\TextBlock';

		parent::__construct($locator, $page_factory);
	}

	/**
	 * Returns class instance, that was placed inside a proxy.
	 *
	 * @return TypifiedElement
	 * @throws ElementNotFoundException When element wasn't found on the page.
	 */
	public function getObject()
	{
		if ( !is_object($this->object) ) {
			$element = $this->locator->find();

			if ( !is_object($element) ) {
				throw new ElementNotFoundException('Element not found by selector: ' . (string)$this->locator);
			}

			/* @var $wrapped_element WebElement */
			$wrapped_element = call_user_func(array($this->wrappedClassName, 'fromNodeElement'), $element, $this->pageFactory);
			$wrapped_element->setContainer($this->getContainer());

			/* @var $object TypifiedElement */
			$object = new $this->className($wrapped_element);
			$object->setName($this->getName());

			$this->object = $object;
		}

		return $this->object;
	}

	/**
	 * Returns name of the entity.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

}
