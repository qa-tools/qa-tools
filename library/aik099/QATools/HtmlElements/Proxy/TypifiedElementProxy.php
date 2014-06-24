<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace aik099\QATools\HtmlElements\Proxy;


use aik099\QATools\HtmlElements\Element\ITypifiedElement;
use aik099\QATools\PageObject\Proxy\AbstractProxy;
use aik099\QATools\PageObject\ElementLocator\IElementLocator;
use aik099\QATools\PageObject\IPageFactory;

/**
 * Class for lazy-proxy creation to ensure, that TypifiedElements are
 * really accessed only at moment, when user needs them.
 *
 * @link http://bit.ly/14TbcR9
 */
class TypifiedElementProxy extends AbstractProxy implements ITypifiedElement
{

	/**
	 * Name of the element.
	 *
	 * @var string
	 */
	private $_name;

	/**
	 * Initializes proxy for AbstractTypifiedElement.
	 *
	 * @param IElementLocator $locator      Element selector.
	 * @param IPageFactory    $page_factory Page factory.
	 * @param string          $name         Name of the element.
	 */
	public function __construct(IElementLocator $locator, IPageFactory $page_factory, $name)
	{
		$this->_name = $name;
		$this->className = '\\aik099\\QATools\\HtmlElements\\Element\\TextBlock';
		$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\ITypifiedElement';

		parent::__construct($locator, $page_factory);
	}

	/**
	 * Returns class instance, that was placed inside a proxy.
	 *
	 * @return ITypifiedElement
	 */
	public function getObject()
	{
		if ( !$this->locatorUsed ) {
			// NodeElement + WebElement(setContainer) + TargetElement(setName) = Proxy.
			$this->locatorUsed = true;
			/* @var $object ITypifiedElement */

			if ( $this->isElementCollection() ) {
				$object = call_user_func(
					array($this->className, 'fromNodeElements'), $this->locateElements(), null, $this->pageFactory
				);
			}
			else {
				$object = call_user_func(
					array($this->className, 'fromNodeElement'), $this->locateElement(), $this->pageFactory
				);
			}

			$object->setName($this->getName());
			$this[] = $object;

			$this->injectContainer();
		}

		return $this->current();
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

	/**
	 * Sets a name of an element.
	 *
	 * This method is used by initialization mechanism and is not intended to be used directly.
	 *
	 * @param string $name Name to set.
	 *
	 * @return self
	 */
	public function setName($name)
	{
		$this->_name = $name;

		return $this;
	}

}
