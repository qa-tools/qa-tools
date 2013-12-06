<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\HtmlElements\PropertyDecorators;


use aik099\QATools\HtmlElements\Annotations\ElementNameAnnotation;
use aik099\QATools\HtmlElements\TypifiedElementProxy;
use aik099\QATools\PageObject\ElementLocators\IElementLocator;
use aik099\QATools\PageObject\ElementLocators\IElementLocatorFactory;
use aik099\QATools\PageObject\IPageFactory;
use aik099\QATools\PageObject\Property;
use aik099\QATools\PageObject\PropertyDecorators\DefaultPropertyDecorator;

/**
 * Default decorator for use with PageFactory. Will decorate 1) all of the
 * WebElement fields and 2) List<WebElement> fields that have @FindBy or
 * @FindBys annotation with a proxy that locates the elements using the passed
 * in ElementLocatorFactory.
 *
 * @method \Mockery\Expectation shouldReceive
 */
class TypifiedPropertyDecorator extends DefaultPropertyDecorator
{

	/**
	 * Creates decorator instance.
	 *
	 * @param IElementLocatorFactory $locator_factory Locator factory.
	 * @param IPageFactory           $page_factory    Page factory.
	 */
	public function __construct(IElementLocatorFactory $locator_factory, IPageFactory $page_factory)
	{
		parent::__construct($locator_factory, $page_factory);

		$this->elementToProxyMapping['\\aik099\\QATools\\HtmlElements\\Elements\\TypifiedElement'] = '\\aik099\\QATools\\HtmlElements\\TypifiedElementProxy';
	}

	/**
	 * Perform actual decoration.
	 *
	 * @param Property        $property The property that may be decorated.
	 * @param IElementLocator $locator  Locator.
	 *
	 * @return TypifiedElementProxy|null
	 */
	protected function doDecorate(Property $property, IElementLocator $locator)
	{
		$proxy_class = $this->getProxyClass($property);

		if ( !$proxy_class ) {
			return null;
		}

		/* @var $proxy TypifiedElementProxy */
		$proxy = new $proxy_class($locator, $this->pageFactory, $this->getElementName($property));
		$proxy->setClassName($property->getDataType());
		$proxy->setContainer($locator->getSearchContext());

		return $proxy;
	}

	/**
	 * Returns name of the element.
	 *
	 * @param Property $property Property, to inspect.
	 *
	 * @return string
	 */
	protected function getElementName(Property $property)
	{
		/* @var $annotations ElementNameAnnotation[] */
		$annotations = $property->getAnnotationsFromPropertyOrClass('@element-name');

		return $annotations ? $annotations[0]->name : (string)$property;
	}

}
