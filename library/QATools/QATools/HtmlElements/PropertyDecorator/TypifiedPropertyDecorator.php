<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\HtmlElements\PropertyDecorator;


use QATools\QATools\HtmlElements\Annotation\ElementNameAnnotation;
use QATools\QATools\PageObject\ElementLocator\IElementLocator;
use QATools\QATools\PageObject\ElementLocator\IElementLocatorFactory;
use QATools\QATools\PageObject\IPageFactory;
use QATools\QATools\PageObject\Proxy\IProxy;
use QATools\QATools\PageObject\Property;
use QATools\QATools\PageObject\PropertyDecorator\DefaultPropertyDecorator;

/**
 * Default decorator for use with PageFactory. Will decorate 1) all of the
 * WebElement fields and 2) List<WebElement> fields that have @FindBy or
 * \@FindBys annotation with a proxy that locates the elements using the passed
 * in ElementLocatorFactory.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class TypifiedPropertyDecorator extends DefaultPropertyDecorator
{

	/**
	 * Typified element interface.
	 *
	 * @var string
	 */
	private $_typifiedElementInterface = '\\QATools\\QATools\\HtmlElements\\Element\\ITypifiedElement';

	/**
	 * Typified element collection.
	 *
	 * @var string
	 */
	private $_typifiedElementCollection = '\\QATools\\QATools\\HtmlElements\\Element\\AbstractTypifiedElementCollection';

	/**
	 * Creates decorator instance.
	 *
	 * @param IElementLocatorFactory $locator_factory Locator factory.
	 * @param IPageFactory           $page_factory    Page factory.
	 */
	public function __construct(IElementLocatorFactory $locator_factory, IPageFactory $page_factory)
	{
		parent::__construct($locator_factory, $page_factory);

		$this->elementToProxyMapping[$this->_typifiedElementCollection] = '\\QATools\\QATools\\HtmlElements\\Proxy\\TypifiedElementCollectionProxy';
		$this->elementToProxyMapping[$this->_typifiedElementInterface] = '\\QATools\\QATools\\HtmlElements\\Proxy\\TypifiedElementProxy';
	}

	/**
	 * Perform actual decoration.
	 *
	 * @param Property        $property The property that may be decorated.
	 * @param IElementLocator $locator  Locator.
	 *
	 * @return IProxy|null
	 */
	protected function doDecorate(Property $property, IElementLocator $locator)
	{
		$proxy_class = $this->getProxyClass($property);

		if ( !$proxy_class ) {
			return null;
		}

		/* @var $proxy IProxy */
		$proxy = new $proxy_class($locator, $this->pageFactory, $this->getElementName($property));
		$proxy->setClassName($property->getDataType());

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

		if ( $annotations && ($annotations[0] instanceof ElementNameAnnotation) ) {
			return $annotations[0]->name;
		}

		return (string)$property;
	}

}
