<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\Proxy;


use aik099\QATools\PageObject\Element\IWebElement;
use aik099\QATools\PageObject\ElementLocator\IElementLocator;
use aik099\QATools\PageObject\Element\WebElement;
use aik099\QATools\PageObject\IPageFactory;

/**
 * Class for lazy-proxy creation to ensure, that WebElements are really accessed only at moment, when user needs them.
 *
 * @method \Mockery\Expectation shouldReceive
 *
 * @link http://bit.ly/14TbcR9
 */
class WebElementProxy extends AbstractProxy implements IWebElement
{

	/**
	 * Initializes proxy for WebElement.
	 *
	 * @param IElementLocator $locator      Element selector.
	 * @param IPageFactory    $page_factory Page factory.
	 */
	public function __construct(IElementLocator $locator, IPageFactory $page_factory = null)
	{
		$this->className = '\\aik099\\QATools\\PageObject\\Element\\WebElement';

		parent::__construct($locator, $page_factory);
	}

	/**
	 * Returns class instance, that was placed inside a proxy.
	 *
	 * @return WebElement
	 */
	public function getObject()
	{
		if ( !is_object($this->object) ) {
			$element = $this->locateElement();

			$this->object = call_user_func(array($this->className, 'fromNodeElement'), $element, $this->pageFactory);
			$this->object->setContainer($this->getContainer());
		}

		return $this->object;
	}

}
