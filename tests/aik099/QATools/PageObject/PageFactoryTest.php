<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\PageObject;


use Mockery as m;
use aik099\QATools\PageObject\Annotation\PageUrlAnnotation;
use aik099\QATools\PageObject\Page;
use aik099\QATools\PageObject\PageFactory;
use aik099\QATools\PageObject\Property;
use aik099\QATools\PageObject\PropertyDecorator\IPropertyDecorator;
use tests\aik099\QATools\TestCase;

class PageFactoryTest extends TestCase
{

	const ANNOTATION_MANAGER_CLASS = '\\mindplay\\annotations\\AnnotationManager';

	/**
	 * Page factory class.
	 *
	 * @var string
	 */
	protected $factoryClass = '\\aik099\\QATools\\PageObject\\PageFactory';

	/**
	 * Page class.
	 *
	 * @var string
	 */
	protected $pageClass = '\\tests\\aik099\\QATools\\PageObject\\Fixture\\Page\\PageChild';

	/**
	 * Decorator class.
	 *
	 * @var string
	 */
	protected $decoratorClass = '\\aik099\\QATools\\PageObject\\PropertyDecorator\\DefaultPropertyDecorator';

	/**
	 * Annotation manager.
	 *
	 * @var \Mockery\MockInterface
	 */
	protected $annotationManager;

	/**
	 * Page factory.
	 *
	 * @var PageFactory
	 */
	protected $realFactory;

	/**
	 * Prepare factory.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->pageFactory->shouldReceive('initPage')->andReturn(\Mockery::self());
		$this->pageFactory->shouldReceive('initElementContainer')->andReturn(\Mockery::self());
		$this->pageFactory->shouldReceive('initElements')->andReturn(\Mockery::self());

		$this->selectorsHandler->shouldReceive('isSelectorRegistered')->andReturn(false);
		$this->selectorsHandler->shouldReceive('registerSelector')->with('se', m::any())->andReturnNull();

		$this->annotationManager = m::mock(self::ANNOTATION_MANAGER_CLASS);

		$this->realFactory = $this->createFactory();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testConstructorWithAnnotationManager()
	{
		$this->assertSame($this->session, $this->realFactory->getSession());
		$this->assertSame($this->annotationManager, $this->realFactory->getAnnotationManager());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testConstructorWithoutAnnotationManager()
	{
		$factory = $this->createFactory(false);
		$this->assertInstanceOf(self::ANNOTATION_MANAGER_CLASS, $factory->getAnnotationManager());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetAnnotationManager()
	{
		$annotation_manager = m::mock(self::ANNOTATION_MANAGER_CLASS);
		$this->assertSame($this->realFactory, $this->realFactory->setAnnotationManager($annotation_manager));
		$this->assertSame($annotation_manager, $this->realFactory->getAnnotationManager());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testCreateDecorator()
	{
		$this->assertInstanceOf($this->decoratorClass, $this->createDefaultDecorator());
	}

	/**
	 * Create default decorator.
	 *
	 * @return IPropertyDecorator
	 */
	protected function createDefaultDecorator()
	{
		$search_context = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');

		return $this->createFactory()->createDecorator($search_context);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testAnnotationRegistry()
	{
		$this->assertArrayHasKey('find-by', $this->annotationManager->registry);
		$this->assertArrayHasKey('page-url', $this->annotationManager->registry);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testInitElementsChaining()
	{
		$decorator = $this->createNullDecorator();
		$search_context = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');

		$this->assertSame($this->realFactory, $this->realFactory->initElements($search_context, $decorator));
	}

	/**
	 * Test description.
	 *
	 * @param string|null $url Url.
	 *
	 * @return void
	 * @dataProvider initPageDataProvider
	 */
	public function testInitPage($url)
	{
		$this->expectPageUrlAnnotation($url);

		/* @var $page Page */
		$page = m::mock($this->pageClass);
		$this->assertSame($this->realFactory, $this->realFactory->initPage($page));
		$this->assertEquals($url, $page->relativeUrl);
	}

	/**
	 * Sets expectation for a specific page url annotation.
	 *
	 * @param string|null $url Url.
	 *
	 * @return void
	 */
	protected function expectPageUrlAnnotation($url = null)
	{
		$annotations = array();

		if ( isset($url) ) {
			$annotation = new PageUrlAnnotation();
			$annotation->url = $url;

			$annotations[] = $annotation;
		}

		$this->annotationManager->shouldReceive('getClassAnnotations')->with(m::any(), '@page-url')->andReturn($annotations);
	}

	/**
	 * Provides url for testing.
	 *
	 * @return array
	 */
	public function initPageDataProvider()
	{
		return array(
			array('TEST-URL'),
			array(null),
		);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testInitElementContainer()
	{
		$element_container = m::mock('\\aik099\\QATools\\PageObject\\Element\\ElementContainer');
		$this->assertSame($this->realFactory, $this->realFactory->initElementContainer($element_container));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testProxyFields()
	{
		$this->annotationManager->shouldReceive('getClassAnnotations')->andReturn(array());
		$this->pageFactory->shouldReceive('createDecorator')->andReturn($this->createNullDecorator());

		$decorator = m::mock($this->decoratorClass);
		$decorator->shouldReceive('decorate')->andReturnUsing(function (Property $property) {
			return $property->name == 'elementWithUse' ? 'OK' : null;
		});

		$page = new $this->pageClass($this->pageFactory);
		$this->realFactory->initElements($page, $decorator);

		$this->assertEquals('OK', $page->elementWithUse);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetPage()
	{
		$factory = $this->createFactory(true, array('initPage', 'initElements', 'createDecorator'));
		$factory->shouldReceive('initPage')->andReturn($factory);
		$factory->shouldReceive('initElements')->andReturn($factory);
		$factory->shouldReceive('createDecorator')->andReturn($this->createNullDecorator());

		$page = $factory->getPage($this->pageClass);
		$this->assertInstanceOf($this->pageClass, $page);
	}

	/**
	 * Creates factory.
	 *
	 * @param boolean $with_annotation_manager Use mock annotation manager.
	 * @param array   $mock_methods            Methods to mock.
	 *
	 * @return PageFactory
	 */
	protected function createFactory($with_annotation_manager = true, array $mock_methods = array())
	{
		$annotation_manager = $with_annotation_manager ? $this->annotationManager : null;

		if ( $mock_methods ) {
			$factory = m::mock(
				$this->factoryClass . '[' . implode(',', $mock_methods) . ']',
				array($this->session, $annotation_manager)
			);

			return $factory;
		}

		return new $this->factoryClass($this->session, $annotation_manager);
	}

	/**
	 * Creates decorator that doesn't decorate anything.
	 *
	 * @return IPropertyDecorator
	 */
	protected function createNullDecorator()
	{
		$decorator = m::mock($this->decoratorClass);
		$decorator->shouldReceive('decorate')->andReturnNull();

		return $decorator;
	}

}
