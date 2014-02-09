<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM\PropertyDecorator;


use aik099\QATools\BEM\Annotation\BEMAnnotation;
use aik099\QATools\BEM\BlockProxy;
use aik099\QATools\BEM\ElementProxy;
use aik099\QATools\BEM\Exception\BEMPageFactoryException;
use aik099\QATools\PageObject\ElementLocator\IElementLocator;
use aik099\QATools\PageObject\Property;
use aik099\QATools\PageObject\PropertyDecorator\DefaultPropertyDecorator;
use aik099\QATools\PageObject\WebElementProxy;

/**
 * Default decorator for use with PageFactory. Will decorate 1) all of the
 * WebElement fields and 2) List<WebElement> fields that have @FindBy or
 * @FindBys annotation with a proxy that locates the elements using the passed
 * in ElementLocatorFactory.
 *
 * @method \Mockery\Expectation shouldReceive
 */
class BEMPropertyDecorator extends DefaultPropertyDecorator
{

	/**
	 * Perform actual decoration.
	 *
	 * @param Property        $property The property that may be decorated.
	 * @param IElementLocator $locator  Locator.
	 *
	 * @return WebElementProxy|null
	 */
	protected function doDecorate(Property $property, IElementLocator $locator)
	{
		if ( $this->isBEMBlock($property) ) {
			return $this->proxyBEMBlock($property, $locator);
		}

		if ( $this->isBEMElement($property) ) {
			return $this->proxyBEMElement($property, $locator);
		}

		return parent::doDecorate($property, $locator);
	}

	/**
	 * Checks, that given class is BEM Block or it's descendant.
	 *
	 * @param Property $property Property.
	 *
	 * @return boolean
	 */
	protected function isBEMBlock(Property $property)
	{
		return $this->classMatches($property->getDataType(), '\\aik099\\QATools\\BEM\\Element\\Block');
	}

	/**
	 * Creates lazy proxy object for a property using given proxy class.
	 *
	 * @param Property        $property Property, that will store created proxy object.
	 * @param IElementLocator $locator  Locator.
	 *
	 * @return BlockProxy
	 * @throws BEMPageFactoryException When block annotation is missing.
	 */
	protected function proxyBEMBlock(Property $property, IElementLocator $locator)
	{
		/* @var $annotations BEMAnnotation[] */
		$annotations = $property->getAnnotationsFromPropertyOrClass('@bem');

		if ( !$annotations ) {
			throw new BEMPageFactoryException('Block must be defined as annotation');
		}

		return new BlockProxy($annotations[0]->block, $locator, $property->getDataType(), $this->pageFactory);
	}

	/**
	 * Checks, that given class is BEM Element or it's descendant.
	 *
	 * @param Property $property Property.
	 *
	 * @return boolean
	 */
	protected function isBEMElement(Property $property)
	{
		return $this->classMatches($property->getDataType(), '\\aik099\\QATools\\BEM\\Element\\Element');
	}

	/**
	 * Creates lazy proxy object for a property using given proxy class.
	 *
	 * @param Property        $property Property, that will store created proxy object.
	 * @param IElementLocator $locator  Locator.
	 *
	 * @return ElementProxy
	 * @throws BEMPageFactoryException When element annotation is missing.
	 */
	protected function proxyBEMElement(Property $property, IElementLocator $locator)
	{
		/* @var $annotations BEMAnnotation[] */
		$annotations = $property->getAnnotations('@bem');

		if ( !$annotations ) {
			throw new BEMPageFactoryException('Element must be defined as annotation');
		}

		return new ElementProxy($annotations[0]->element, $locator, $property->getDataType());
	}

}
