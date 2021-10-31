<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\PropertyDecorator;


use Behat\Mink\Element\NodeElement;
use QATools\QATools\PageObject\ElementLocator\DefaultElementLocatorFactory;
use QATools\QATools\PageObject\ElementLocator\IElementLocator;
use QATools\QATools\PageObject\ElementLocator\IElementLocatorFactory;
use QATools\QATools\PageObject\IPageFactory;
use QATools\QATools\PageObject\ISearchContext;
use QATools\QATools\PageObject\Property;
use QATools\QATools\PageObject\PropertyDecorator\IPropertyDecorator;
use QATools\QATools\PageObject\Proxy\IProxy;
use QATools\QATools\PageObject\Proxy\WebElementProxy;
use mindplay\annotations\AnnotationCache;
use mindplay\annotations\AnnotationManager;
use Mockery as m;
use tests\QATools\QATools\TestCase;

class DefaultPropertyDecoratorTest extends TestCase
{

	/**
	 * Locator class.
	 *
	 * @var string
	 */
	protected $locatorClass = '\\QATools\\QATools\\PageObject\\ElementLocator\\IElementLocator';

	/**
	 * Decorator class.
	 *
	 * @var string
	 */
	protected $decoratorClass = '\\QATools\\QATools\\PageObject\\PropertyDecorator\\DefaultPropertyDecorator';

	/**
	 * Property.
	 *
	 * @var Property
	 */
	protected $property;

	/**
	 * Locator.
	 *
	 * @var IElementLocator
	 */
	protected $locator;

	/**
	 * Locator factory.
	 *
	 * @var IElementLocatorFactory
	 */
	protected $locatorFactory;

	/**
	 * Decorator.
	 *
	 * @var IPropertyDecorator
	 */
	protected $decorator;

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		parent::setUpTest();

		$this->property = m::mock('\\QATools\\QATools\\PageObject\\Property');
		$this->locator = m::mock($this->locatorClass);
		$this->locatorFactory = m::mock('\\QATools\\QATools\\PageObject\\ElementLocator\\IElementLocatorFactory');

		if ( $this->getName() == 'testEmptyLocatorPreventsDecoration' ) {
			$this->locatorFactory->shouldReceive('createLocator');
		}
		else {
			$this->locatorFactory->shouldReceive('createLocator')->andReturn($this->locator);
		}

		$this->decorator = new $this->decoratorClass($this->locatorFactory, $this->pageFactory);
	}

	public function testEmptyLocatorPreventsDecoration()
	{
		$this->property->shouldReceive('isSimpleDataType')->andReturn(false);
		$this->property->shouldReceive('getDataType')->andReturn(get_class($this));

		$this->assertNull($this->decorator->decorate($this->property));
	}

	/**
	 * @dataProvider decorationAbortingDataProvider
	 */
	public function testDecorationAborting($data_type, $is_simple_data_type)
	{
		$this->property->shouldReceive('getDataType')->andReturn($data_type);
		$this->property->shouldReceive('isSimpleDataType')->andReturn($is_simple_data_type);

		$this->assertNull($this->decorator->decorate($this->property));
	}

	/**
	 * Returns conditions for decoration prevention.
	 *
	 * @return array
	 */
	public function decorationAbortingDataProvider()
	{
		return array(
			array(false, false),
			array(__CLASS__, true),
			array('\\QATools\\QATools\\PageObject\\IPageFactory', false),
		);
	}

	public function testNotExistentClassPreventsDecoration()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\PageFactoryException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\PageFactoryException::TYPE_UNKNOWN_CLASS);
		$this->expectExceptionMessage('"PropertyName" element not recognised. "\QATools\QATools\PageObject\MissingClass" class not found');

		$this->property->shouldReceive('__toString')->andReturn('PropertyName');
		$this->property->shouldReceive('isSimpleDataType')->andReturn(false);
		$this->property->shouldReceive('getDataType')->andReturn('\\QATools\\QATools\\PageObject\\MissingClass');

		$this->decorator->decorate($this->property);
	}

	public function testUnknownProxyClassPreventsDecoration()
	{
		$this->property->shouldReceive('getDataType')->andReturn(__CLASS__);
		$this->property->shouldReceive('isSimpleDataType')->andReturn(false);

		$this->assertNull($this->decorator->decorate($this->property));
	}

	/**
	 * @return WebElementProxy
	 * @dataProvider proxyDataProvider
	 */
	public function testProxyWebElement($element_class, $proxy_class)
	{
		$search_context = m::mock('\\QATools\\QATools\\PageObject\\ISearchContext');
		$this->locator->shouldReceive('getSearchContext')->andReturn($search_context);

		$node_elements = array(
			$this->createNodeElement('xpath1'),
			$this->createNodeElement('xpath2'),
		);

		$this->locator->shouldReceive('findAll')->andReturn($node_elements);

		$this->property->shouldReceive('isSimpleDataType')->andReturn(false);
		$this->property->shouldReceive('getDataType')->andReturn($element_class);

		$proxy = $this->decorator->decorate($this->property);
		$this->assertProxy($proxy, $proxy_class, $element_class);

		if ( strpos($proxy_class, 'Collection') !== false ) {
			foreach ( $proxy->getObject() as $index => $proxied_element ) {
				$this->assertEquals(
					$node_elements[$index]->getXpath(),
					$proxied_element->getXpath(),
					'The method call is proxied to collection element.'
				);
			}
		}
		else {
			$this->assertEquals($node_elements[0]->getXpath(), $proxy->getXpath(), 'The method call is proxied.');
		}

		return $proxy;
	}

	/**
	 * Creates NodeElement mock.
	 *
	 * @param string|null $xpath XPath of the element.
	 *
	 * @return NodeElement
	 */
	protected function createNodeElement($xpath = null)
	{
		$element = parent::createNodeElement($xpath);

		if ( $this->getName(false) === 'testProxyWebElement' ) {
			$this->selectorsHandler
				->shouldReceive('selectorToXpath')
				->with('se', array('xpath' => $xpath))
				->andReturn($xpath);
		}

		return $element;
	}

	/**
	 * Verifies, that proxy did that's needed.
	 *
	 * @param IProxy $proxy         Proxy object.
	 * @param string $proxy_class   Proxy class.
	 * @param string $element_class Element class given to proxy.
	 *
	 * @return void
	 */
	protected function assertProxy(IProxy $proxy, $proxy_class, $element_class)
	{
		$this->assertInstanceOf($proxy_class, $proxy);
		$this->assertInstanceOf($element_class, $proxy->getObject());
	}

	public function proxyDataProvider()
	{
		return array(
			'web element' => array(
				'\\QATools\\QATools\\PageObject\\Element\\WebElement',
				'\\QATools\\QATools\\PageObject\\Proxy\\WebElementProxy',
			),
			'web element collection' => array(
				'\\QATools\\QATools\\PageObject\\Element\\WebElementCollection',
				'\\QATools\\QATools\\PageObject\\Proxy\\WebElementCollectionProxy',
			),
		);
	}

	public function testInterfacesAreNotDecorated()
	{
		/** @var $search_context ISearchContext */
		$search_context = m::mock('\\QATools\\QATools\\PageObject\\ISearchContext');
		$annotation_manager = new AnnotationManager();
		$annotation_manager->cache = new AnnotationCache(sys_get_temp_dir());

		/** @var $page_factory IPageFactory */
		$page_factory = m::mock('\\QATools\\QATools\\PageObject\\IPageFactory');
		$locator_factory = new DefaultElementLocatorFactory($search_context, $annotation_manager);

		/** @var $decorator IPropertyDecorator */
		$decorator = new $this->decoratorClass($locator_factory, $page_factory);
		$reflection_class = new \ReflectionClass(
			'\\tests\\QATools\QATools\\PageObject\\Fixture\\InterfaceAnnotatedClass'
		);

		$properties = $reflection_class->getProperties();

		foreach ( $properties as $reflected_property ) {
			$property = new Property($reflected_property, $annotation_manager);

			$proxy = $decorator->decorate($property);

			$this->assertNull($proxy);
		}
	}

}
