<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM\Proxy;


use aik099\QATools\BEM\Element\IElement;
use aik099\QATools\BEM\ElementLocator\BEMElementLocator;
use aik099\QATools\PageObject\Element\WebElement;
use aik099\QATools\PageObject\IPageFactory;

/**
 * Class for lazy-proxy creation to ensure, that BEM elements are really accessed only at moment, when user needs them
 *
 * @link http://bit.ly/14TbcR9
 *
 * @method \Mockery\Expectation shouldReceive
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
		$this->className = '\\aik099\\QATools\\BEM\\Element\\Element';
		$this->elementClass = '\\aik099\\QATools\\BEM\\Element\\IElement';

		parent::__construct($name, $locator, $page_factory);
	}

	/**
	 * Returns element instance.
	 *
	 * @return IElement
	 */
	public function getObject()
	{
		if ( !is_object($this->object) ) {
			$web_element = WebElement::fromNodeElement($this->locateElement());

			$this->object = new $this->className($this->getName(), $web_element);
			$this->object->setContainer($this->getContainer());
		}

		return $this->object;
	}

}
