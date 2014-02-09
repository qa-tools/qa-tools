<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\PropertyDecorator;


use aik099\QATools\PageObject\ElementLocator\IElementLocator;
use aik099\QATools\PageObject\ElementLocator\IElementLocatorFactory;
use aik099\QATools\PageObject\Exception\PageFactoryException;
use aik099\QATools\PageObject\IPageFactory;
use aik099\QATools\PageObject\Property;
use aik099\QATools\PageObject\WebElementProxy;


/**
 * Default decorator for use with PageFactory. Will decorate 1) all of the
 * WebElement fields and 2) List<WebElement> fields that have @FindBy or
 * @FindBys annotation with a proxy that locates the elements using the passed
 * in ElementLocatorFactory.
 *
 * @method \Mockery\Expectation shouldReceive
 */
class DefaultPropertyDecorator implements IPropertyDecorator
{

	/**
	 * Locator factory.
	 *
	 * @var IElementLocatorFactory
	 */
	protected $locatorFactory;

	/**
	 * Page factory.
	 *
	 * @var IPageFactory
	 */
	protected $pageFactory;

	/**
	 * Mapping between element classes, that factory supports and how to proxy them.
	 *
	 * @var array
	 */
	protected $elementToProxyMapping = array(
		'\\aik099\\QATools\\PageObject\\Element\\WebElement' => '\\aik099\\QATools\\PageObject\\WebElementProxy',
	);

	/**
	 * Creates decorator instance.
	 *
	 * @param IElementLocatorFactory $locator_factory Locator factory.
	 * @param IPageFactory           $page_factory    Page factory.
	 */
	public function __construct(IElementLocatorFactory $locator_factory, IPageFactory $page_factory)
	{
		$this->locatorFactory = $locator_factory;
		$this->pageFactory = $page_factory;
	}

	/**
	 * This method is called by PageFactory on all properties to decide how to decorate the property.
	 *
	 * @param Property $property The property that may be decorated.
	 *
	 * @return WebElementProxy
	 */
	public function decorate(Property $property)
	{
		$locator = $this->locatorFactory->createLocator($property);

		if ( !$this->canDecorate($property) || $locator == null ) {
			return null;
		}

		return $this->doDecorate($property, $locator);
	}

	/**
	 * Checks if a property can be decorated.
	 *
	 * @param Property $property The property that may be decorated.
	 *
	 * @return boolean
	 * @throws PageFactoryException When class of non-existing element discovered in property's @var annotation.
	 */
	protected function canDecorate(Property $property)
	{
		$data_type = $property->getDataType();

		if ( !$data_type || $property->isSimpleDataType() || interface_exists($data_type) ) {
			return false;
		}

		if ( !class_exists($data_type) ) {
			throw new PageFactoryException(
				sprintf('"%s" element not recognised. "%s" class not found.', $property, $data_type)
			);
		}

		return true;
	}

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
		$proxy_class = $this->getProxyClass($property);

		if ( !$proxy_class ) {
			return null;
		}

		/* @var $proxy WebElementProxy */
		$proxy = new $proxy_class($locator, $this->pageFactory);
		$proxy->setClassName($property->getDataType());
		$proxy->setContainer($locator->getSearchContext());

		return $proxy;
	}

	/**
	 * Returns proxy class, that can be used alongside with element class of a property.
	 *
	 * @param Property $property Property.
	 *
	 * @return string
	 */
	protected function getProxyClass(Property $property)
	{
		$data_type = $property->getDataType();

		foreach ( $this->elementToProxyMapping as $element_class => $proxy_class ) {
			if ( $this->classMatches($data_type, $element_class) ) {
				return $proxy_class;
			}
		}

		return false;
	}

	/**
	 * Ensures that 2 given classes has a relation.
	 *
	 * @param string $class_name    Class name to test.
	 * @param string $descendant_of Required descendant class.
	 *
	 * @return boolean
	 */
	protected function classMatches($class_name, $descendant_of)
	{
		$class_name = ltrim($class_name, '\\');
		$descendant_of = ltrim($descendant_of, '\\');

		return $class_name == $descendant_of || in_array($descendant_of, class_parents($class_name));
	}

}
