<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\BEM\Proxy;


use QATools\QATools\BEM\Element\IElement;
use QATools\QATools\BEM\ElementLocator\BEMElementLocator;
use QATools\QATools\PageObject\Element\WebElement;
use QATools\QATools\PageObject\IPageFactory;

/**
 * Class for lazy-proxy creation to ensure, that BEM elements are really accessed only at moment, when user needs them
 *
 * @link http://bit.ly/14TbcR9
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class ElementProxy extends AbstractPartProxy implements IElement
{

	/**
	 * Initializes BEM element proxy.
	 *
	 * @param string            $name         Name.
	 * @param BEMElementLocator $locator      Locator.
	 * @param IPageFactory      $page_factory Page factory.
	 */
	public function __construct($name, BEMElementLocator $locator, IPageFactory $page_factory = null)
	{
		$this->className = '\\QATools\\QATools\\BEM\\Element\\Element';
		$this->elementClass = '\\QATools\\QATools\\BEM\\Element\\IElement';

		parent::__construct($name, $locator, $page_factory);
	}

	/**
	 * Locates object inside proxy.
	 *
	 * @return void
	 */
	protected function locateObject()
	{
		if ( is_object($this->object) ) {
			return;
		}

		$web_element = WebElement::fromNodeElement($this->locateElement());

		$this->object = new $this->className($this->getName(), $web_element);
	}

	/**
	 * Returns element instance.
	 *
	 * @return IElement
	 */
	public function getObject()
	{
		$this->locateObject();

		return $this->object;
	}

}
