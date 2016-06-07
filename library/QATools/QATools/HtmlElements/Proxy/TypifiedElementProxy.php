<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\HtmlElements\Proxy;


use QATools\QATools\HtmlElements\Element\ITypifiedElement;
use QATools\QATools\PageObject\Proxy\AbstractProxy;
use QATools\QATools\PageObject\ElementLocator\IElementLocator;
use QATools\QATools\PageObject\IPageFactory;

/**
 * Class for lazy-proxy creation to ensure, that TypifiedElements are
 * really accessed only at moment, when user needs them.
 *
 * @link http://bit.ly/qa-tools-page-factory-lazy-initialization
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
		$this->className = '\\QATools\\QATools\\HtmlElements\\Element\\TextBlock';
		$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\ITypifiedElement';

		parent::__construct($locator, $page_factory);
	}

	/**
	 * Locates object inside proxy.
	 *
	 * @return void
	 */
	protected function locateObject()
	{
		if ( $this->locatorUsed ) {
			return;
		}

		// NodeElement + WebElement + TargetElement(setName) = Proxy.
		$this->locatorUsed = true;

		foreach ( $this->locateElements() as $element ) {
			/* @var $object ITypifiedElement */
			$object = call_user_func(
				array($this->className, 'fromNodeElement'),
				$element,
				$this->pageFactory
			);

			$object->setName($this->getName());
			$this[] = $object;
		}
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
