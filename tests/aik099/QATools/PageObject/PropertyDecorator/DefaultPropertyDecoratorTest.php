<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\aik099\QATools\PageObject\PropertyDecorator;


use aik099\QATools\PageObject\ElementLocator\DefaultElementLocatorFactory;
use aik099\QATools\PageObject\ElementLocator\IElementLocator;
use aik099\QATools\PageObject\ElementLocator\IElementLocatorFactory;
use aik099\QATools\PageObject\IPageFactory;
use aik099\QATools\PageObject\ISearchContext;
use aik099\QATools\PageObject\Property;
use aik099\QATools\PageObject\PropertyDecorator\IPropertyDecorator;
use aik099\QATools\PageObject\Proxy\IProxy;
use aik099\QATools\PageObject\Proxy\WebElementProxy;
use mindplay\annotations\AnnotationCache;
use mindplay\annotations\AnnotationManager;
use Mockery as m;
use tests\aik099\QATools\TestCase;

class DefaultPropertyDecoratorTest extends TestCase
{

	/**
	 * Locator class.
	 *
	 * @var string
	 */
	protected $locatorClass = '\\aik099\\QATools\\PageObject\\ElementLocator\\IElementLocator';

	/**
	 * Decorator class.
	 *
	 * @var string
	 */
	protected $decoratorClass = '\\aik099\\QATools\\PageObject\\PropertyDecorator\\DefaultPropertyDecorator';

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

	protected function setUp()
	{
		parent::setUp();

		$this->property = m::mock('\\aik099\\QATools\\PageObject\\Property');
		$this->locator = m::mock($this->locatorClass);
		$this->locatorFactory = m::mock('\\aik099\\QATools\\PageObject\\ElementLocator\\IElementLocatorFactory');

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
			array('\\aik099\\QATools\\PageObject\\IPageFactory', false),
		);
	}

	/**
	 * @expectedException \aik099\QATools\PageObject\Exception\PageFactoryException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\PageFactoryException::TYPE_UNKNOWN_CLASS
	 */
	public function testNotExistentClassPreventsDecoration()
	{
		$this->property->shouldReceive('__toString')->andReturn('OK');
		$this->property->shouldReceive('isSimpleDataType')->andReturn(false);
		$this->property->shouldReceive('getDataType')->andReturn('\\aik099\\QATools\\PageObject\\MissingClass');

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
		$search_context = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');
		$this->locator->shouldReceive('getSearchContext')->andReturn($search_context);

		$node_element = $this->createNodeElement();
		$this->locator->shouldReceive('findAll')->andReturn(array($node_element));

		$this->property->shouldReceive('isSimpleDataType')->andReturn(false);
		$this->property->shouldReceive('getDataType')->andReturn($element_class);

		$proxy = $this->decorator->decorate($this->property);
		$this->assertProxy($proxy, $proxy_class, $search_context, $element_class);
		$this->assertEquals($node_element->getXpath(), $proxy->getXpath());

		return $proxy;
	}

	/**
	 * Verifies, that proxy did that's needed.
	 *
	 * @param IProxy         $proxy           Proxy object.
	 * @param string         $proxy_class     Proxy class.
	 * @param ISearchContext $proxy_container Container object set to proxy.
	 * @param string         $element_class   Element class given to proxy.
	 *
	 * @return void
	 */
	protected function assertProxy(IProxy $proxy, $proxy_class, ISearchContext $proxy_container, $element_class)
	{
		$this->assertInstanceOf($proxy_class, $proxy);
		$this->assertInstanceOf($element_class, $proxy->getObject());
		$this->assertSame($proxy_container, $proxy->getContainer());
	}

	public function proxyDataProvider()
	{
		return array(
			array('\\aik099\\QATools\\PageObject\\Element\\WebElement', '\\aik099\\QATools\\PageObject\\Proxy\\WebElementProxy'),
		);
	}

	public function testInterfacesAreNotDecorated()
	{
		/** @var $search_context ISearchContext */
		$search_context = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');
		$annotation_manager = new AnnotationManager();
		$annotation_manager->cache = new AnnotationCache(sys_get_temp_dir());

		/** @var $page_factory IPageFactory */
		$page_factory = m::mock('\\aik099\\QATools\\PageObject\\IPageFactory');
		$locator_factory = new DefaultElementLocatorFactory($search_context, $annotation_manager);

		/** @var $decorator IPropertyDecorator */
		$decorator = new $this->decoratorClass($locator_factory, $page_factory);
		$reflection_class = new \ReflectionClass(
			'\\tests\\aik099\QATools\\PageObject\\Fixture\\InterfaceAnnotatedClass'
		);

		$properties = $reflection_class->getProperties();

		foreach ( $properties as $reflected_property ) {
			$property = new Property($reflected_property, $annotation_manager);

			$proxy = $decorator->decorate($property);

			$this->assertNull($proxy);
		}
	}

}
