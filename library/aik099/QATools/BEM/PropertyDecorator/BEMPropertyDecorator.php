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
use aik099\QATools\BEM\BEMPage;
use aik099\QATools\BEM\Element\IBlock;
use aik099\QATools\PageObject\ElementLocator\IElementLocator;
use aik099\QATools\PageObject\ElementLocator\IElementLocatorFactory;
use aik099\QATools\PageObject\Exception\AnnotationException;
use aik099\QATools\PageObject\IPageFactory;
use aik099\QATools\PageObject\Proxy\IProxy;
use aik099\QATools\PageObject\Property;
use aik099\QATools\PageObject\PropertyDecorator\DefaultPropertyDecorator;

/**
 * Default decorator for use with PageFactory. Will decorate 1) all of the
 * WebElement fields and 2) List<WebElement> fields that have @FindBy or
 * @FindBys annotation with a proxy that locates the elements using the passed
 * in ElementLocatorFactory.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class BEMPropertyDecorator extends DefaultPropertyDecorator
{

	/**
	 * BEM block interface.
	 *
	 * @var string
	 */
	private $_blockInterface = '\\aik099\\QATools\\BEM\\Element\\IBlock';

	/**
	 * BEM element interface.
	 *
	 * @var string
	 */
	private $_elementInterface = '\\aik099\\QATools\\BEM\\Element\\IElement';

	/**
	 * Creates decorator instance.
	 *
	 * @param IElementLocatorFactory $locator_factory Locator factory.
	 * @param IPageFactory           $page_factory    Page factory.
	 */
	public function __construct(IElementLocatorFactory $locator_factory, IPageFactory $page_factory)
	{
		parent::__construct($locator_factory, $page_factory);

		$this->elementToProxyMapping[$this->_blockInterface] = '\\aik099\\QATools\\BEM\\Proxy\\BlockProxy';
		$this->elementToProxyMapping[$this->_elementInterface] = '\\aik099\\QATools\\BEM\\Proxy\\ElementProxy';
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

		if ( $this->_isBEMBlock($property) || $this->_isBEMElement($property) ) {
			/* @var $annotations BEMAnnotation[] */
			$annotations = $property->getAnnotationsFromPropertyOrClass('@bem');
			$this->_assertAnnotationUsage($annotations, $locator);

			if ( !($annotations[0] instanceof BEMAnnotation) ) {
				return null;
			}

			$name = $this->_isBEMBlock($property) ? $annotations[0]->block : $annotations[0]->element;

			/* @var $proxy IProxy */
			$proxy = new $proxy_class($name, $locator, $this->pageFactory);
			$proxy->setClassName($property->getDataType());
			$proxy->setContainer($locator->getSearchContext());

			return $proxy;
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
	private function _isBEMBlock(Property $property)
	{
		return $this->classMatches($property->getDataType(), $this->_blockInterface);
	}

	/**
	 * Checks, that given class is BEM Element or it's descendant.
	 *
	 * @param Property $property Property.
	 *
	 * @return boolean
	 */
	private function _isBEMElement(Property $property)
	{
		return $this->classMatches($property->getDataType(), $this->_elementInterface);
	}

	/**
	 * Verifies, that annotations are being correctly used.
	 *
	 * @param array           $annotations Annotations.
	 * @param IElementLocator $locator     Locator.
	 *
	 * @return void
	 * @throws AnnotationException When annotation is being used incorrectly.
	 */
	private function _assertAnnotationUsage(array $annotations, IElementLocator $locator)
	{
		if ( !$annotations || !($annotations[0] instanceof BEMAnnotation) ) {
			throw new AnnotationException(
				'BEM block/element must be specified as annotation',
				AnnotationException::TYPE_REQUIRED
			);
		}

		/** @var BEMAnnotation $annotation */
		$annotation = $annotations[0];

		if ( ($annotation->element && $annotation->block) || (!$annotation->element && !$annotation->block) ) {
			throw new AnnotationException(
				"Either 'block' or 'element' key with non-empty value must be specified in the annotation",
				AnnotationException::TYPE_INCORRECT_USAGE
			);
		}
		elseif ( $annotation->element && !($locator->getSearchContext() instanceof IBlock) ) {
			throw new AnnotationException(
				'BEM element can only be used in Block sub-class (or any class, implementing IBlock interface) property',
				AnnotationException::TYPE_INCORRECT_USAGE
			);
		}
		elseif ( $annotation->block && !($locator->getSearchContext() instanceof BEMPage) ) {
			throw new AnnotationException(
				'BEM block can only be used in BEMPage sub-class property',
				AnnotationException::TYPE_INCORRECT_USAGE
			);
		}
	}

}
