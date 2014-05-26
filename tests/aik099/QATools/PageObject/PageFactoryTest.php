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


use aik099\QATools\PageObject\Annotation\PageUrlAnnotation;
use aik099\QATools\PageObject\Config\Config;
use aik099\QATools\PageObject\Page;
use aik099\QATools\PageObject\PageFactory;
use aik099\QATools\PageObject\Property;
use aik099\QATools\PageObject\PropertyDecorator\IPropertyDecorator;
use aik099\QATools\PageObject\Url\IUrlBuilderFactory;
use Mockery as m;
use tests\aik099\QATools\TestCase;

class PageFactoryTest extends TestCase
{

	const ANNOTATION_MANAGER_CLASS = '\\mindplay\\annotations\\AnnotationManager';

	const URL_BUILDER_INTERFACE = '\\aik099\\QATools\\PageObject\\Url\\IUrlBuilder';

	const URL_BUILDER_FACTORY_INTERFACE = '\\aik099\\QATools\\PageObject\\Url\\IUrlBuilderFactory';

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
	 * The url builder factory.
	 *
	 * @var IUrlBuilderFactory
	 */
	protected $urlBuilderFactory;

	/**
	 * Page factory config.
	 *
	 * @var Config
	 */
	protected $config;

	protected function setUp()
	{
		parent::setUp();

		$this->pageFactory->shouldReceive('initPage')->andReturn(\Mockery::self());
		$this->pageFactory->shouldReceive('initElementContainer')->andReturn(\Mockery::self());
		$this->pageFactory->shouldReceive('initElements')->andReturn(\Mockery::self());

		$this->selectorsHandler->shouldReceive('isSelectorRegistered')->andReturn(false);
		$this->selectorsHandler->shouldReceive('registerSelector')->with('se', m::any())->andReturnNull();

		$this->annotationManager = m::mock(self::ANNOTATION_MANAGER_CLASS);
		$this->urlBuilderFactory = m::mock(self::URL_BUILDER_FACTORY_INTERFACE);
		$this->config = new Config(array('base_url' => 'http://domain.tld'));

		$this->realFactory = $this->createFactory();
	}

	public function testConstructorWithAnnotationManager()
	{
		$this->assertSame($this->session, $this->realFactory->getSession());
		$this->assertSame($this->annotationManager, $this->realFactory->getAnnotationManager());
	}

	public function testConstructorWithoutAnnotationManager()
	{
		$factory = $this->createFactory(false);
		$this->assertInstanceOf(self::ANNOTATION_MANAGER_CLASS, $factory->getAnnotationManager());
	}

	public function testSetAnnotationManager()
	{
		$annotation_manager = m::mock(self::ANNOTATION_MANAGER_CLASS);
		$this->assertSame($this->realFactory, $this->realFactory->setAnnotationManager($annotation_manager));
		$this->assertSame($annotation_manager, $this->realFactory->getAnnotationManager());
	}

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

	public function testAnnotationRegistry()
	{
		$this->assertArrayHasKey('find-by', $this->annotationManager->registry);
		$this->assertArrayHasKey('page-url', $this->annotationManager->registry);
	}

	public function testInitElementsChaining()
	{
		$decorator = $this->createNullDecorator();
		$search_context = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');

		$this->assertSame($this->realFactory, $this->realFactory->initElements($search_context, $decorator));
	}

	/**
	 * @dataProvider initPageDataProvider
	 */
	public function testInitPage($url, array $params, $use_url_builder)
	{
		$this->expectPageUrlAnnotation($url, $params);

		/* @var $page Page */
		$page = m::mock($this->pageClass);
		$urlBuilder = m::mock(self::URL_BUILDER_INTERFACE);

		$this->realFactory->setUrlBuilderFactory($this->urlBuilderFactory);
		$this->urlBuilderFactory
			->shouldReceive('getUrlBuilder')
			->with($url, $params, 'http://domain.tld')
			->times(isset($url) ? 1 : 0)
			->andReturn($urlBuilder);

		$page->shouldReceive('setUrlBuilder')->times($use_url_builder ? 1 : 0)->andReturn($page);

		$this->assertSame($this->realFactory, $this->realFactory->initPage($page));
	}

	/**
	 * Sets expectation for a specific page url annotation.
	 *
	 * @param string|null $url    Url.
	 * @param array       $params Get params.
	 *
	 * @return void
	 */
	protected function expectPageUrlAnnotation($url = null, array $params = array())
	{
		$annotations = array();

		if ( isset($url) ) {
			$annotation = new PageUrlAnnotation();
			$annotation->url = $url;
			$annotation->params = $params;

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
			array('TEST-URL', array(), true),
			array('TEST-URL', array('param' => 'value'), true),
			array('TEST-URL?param=value', array(), true),
			array('TEST-URL?param1=value1', array('param2' => 'value2'), true),
			array('TEST-URL#anchor', array(), true),
			array('TEST-URL#anchor', array('param' => 'value'), true),
			array('TEST-URL?param=value#anchor', array(), true),
			array('TEST-URL?param1=value1#anchor', array('param2' => 'value2'), true),
			array(null, array(), false),
		);
	}

	public function testInitElementContainer()
	{
		$element_container = m::mock('\\aik099\\QATools\\PageObject\\Element\\AbstractElementContainer');
		$this->assertSame($this->realFactory, $this->realFactory->initElementContainer($element_container));
	}

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

	public function testGetPage()
	{
		$factory = $this->createFactory(true, array('initPage', 'initElements', 'createDecorator'));
		$factory->shouldReceive('initPage')->andReturn($factory);
		$factory->shouldReceive('initElements')->andReturn($factory);
		$factory->shouldReceive('createDecorator')->andReturn($this->createNullDecorator());

		$page = $factory->getPage($this->pageClass);
		$this->assertInstanceOf($this->pageClass, $page);
	}

	public function testGetUrlBuilderFactory()
	{
		/* @var IUrlBuilderFactory $url_builder_factory */
		$url_builder_factory = m::mock(self::URL_BUILDER_FACTORY_INTERFACE);

		$this->realFactory->setUrlBuilderFactory($url_builder_factory);
		$this->assertEquals($url_builder_factory, $this->realFactory->getUrlBuilderFactory());
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
		/** @var PageFactory $factory */

		if ( $mock_methods ) {
			$factory = m::mock(
				$this->factoryClass . '[' . implode(',', $mock_methods) . ']',
				array($this->session, $this->config)
			);
		}
		else {
			$factory = new $this->factoryClass($this->session, $this->config);
		}

		if ( $with_annotation_manager ) {
			$factory->setAnnotationManager($this->annotationManager);
		}

		return $factory;
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
