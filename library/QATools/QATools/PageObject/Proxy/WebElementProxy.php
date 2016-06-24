<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\Proxy;


use QATools\QATools\PageObject\Element\IWebElement;
use QATools\QATools\PageObject\ElementLocator\IElementLocator;
use QATools\QATools\PageObject\IPageFactory;

/**
 * Class for lazy-proxy creation to ensure, that WebElements are really accessed only at moment, when user needs them.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 *
 * @link http://bit.ly/qa-tools-page-factory-lazy-initialization
 */
class WebElementProxy extends AbstractProxy implements IWebElement
{

	/**
	 * Initializes proxy for WebElement.
	 *
	 * @param IElementLocator $locator      Element selector.
	 * @param IPageFactory    $page_factory Page factory.
	 */
	public function __construct(IElementLocator $locator, IPageFactory $page_factory)
	{
		if ( !$this->className ) {
			$this->className = '\\QATools\\QATools\\PageObject\\Element\\WebElement';
		}

		if ( !$this->elementClass ) {
			$this->elementClass = '\\QATools\\QATools\\PageObject\\Element\\IWebElement';
		}

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

		// NodeElement + TargetElement = Proxy.
		$this->locatorUsed = true;

		foreach ( $this->locateElements() as $element ) {
			/* @var $object IWebElement */
			$object = call_user_func(
				array($this->className, 'fromNodeElement'),
				$element,
				$this->pageFactory
			);

			$this[] = $object;
		}
	}

}
