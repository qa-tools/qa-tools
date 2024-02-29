<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject;


use mindplay\annotations\AnnotationManager;
use Mockery as m;
use QATools\QATools\PageObject\Annotation\MatchUrlComponentAnnotation;
use QATools\QATools\PageObject\Annotation\MatchUrlExactAnnotation;
use QATools\QATools\PageObject\Annotation\MatchUrlRegexpAnnotation;
use QATools\QATools\PageObject\Annotation\PageUrlAnnotation;
use QATools\QATools\PageObject\Config\Config;
use QATools\QATools\PageObject\Container;
use QATools\QATools\PageObject\Page;
use QATools\QATools\PageObject\PageFactory;
use QATools\QATools\PageObject\Property;
use QATools\QATools\PageObject\PropertyDecorator\IPropertyDecorator;
use tests\QATools\QATools\PageObject\Fixture\Page\PageChild;
use tests\QATools\QATools\TestCase;

class PageFactoryTest extends TestCase
{

	const ANNOTATION_MANAGER_CLASS = '\\mindplay\\annotations\\AnnotationManager';

	const URL_BUILDER_INTERFACE = '\\QATools\\QATools\\PageObject\\Url\\IBuilder';

	const URL_FACTORY_INTERFACE = '\\QATools\\QATools\\PageObject\\Url\\IUrlFactory';

	const URL_NORMALIZER_CLASS = '\\QATools\\QATools\\PageObject\\Url\\Normalizer';

	/**
	 * Page factory class.
	 *
	 * @var string
	 */
	protected $factoryClass = '\\QATools\\QATools\\PageObject\\PageFactory';

	/**
	 * Page class.
	 *
	 * @var string
	 */
	protected $pageClass = 'tests\\QATools\\QATools\\PageObject\\Fixture\\Page\\PageChild';

	/**
	 * Decorator class.
	 *
	 * @var string
	 */
	protected $decoratorClass = '\\QATools\\QATools\\PageObject\\PropertyDecorator\\DefaultPropertyDecorator';

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
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		parent::setUpTest();

		$this->container = new Container();

		$this->pageFactory->shouldReceive('initPage')->andReturn(\Mockery::self());
		$this->pageFactory->shouldReceive('initElementContainer')->andReturn(\Mockery::self());
		$this->pageFactory->shouldReceive('initElements')->andReturn(\Mockery::self());

		$this->selectorsHandler->shouldReceive('isSelectorRegistered')->andReturn(false);
		$this->selectorsHandler->shouldReceive('registerSelector')->with('se', m::any());

		$this->annotationManager = m::mock(self::ANNOTATION_MANAGER_CLASS);
		$config = new Config(array('base_url' => 'http://domain.tld'));

		$this->container['config'] = $config;

		if ( $this->getName(false) === 'testGetPage' ) {
			$parts = explode('\\', $this->pageClass);
			array_pop($parts);

			$config->setOption('page_namespace_prefix', implode('\\', $parts) . '\\');
		}

		$this->realFactory = $this->createFactory();
	}

	public function testCreateFactoryWithoutContainer()
	{
		$factory = new $this->factoryClass($this->session);

		$this->assertInstanceOf($this->factoryClass, $factory);
	}

	public function testCreateFactoryWithContainer()
	{
		$factory = new $this->factoryClass($this->session, $this->container);

		$this->assertInstanceOf($this->factoryClass, $factory);
	}

	public function testCreateFactoryWithConfig()
	{
		$config = new Config(array('base_url' => 'http://domain.tld'));
		$factory = new $this->factoryClass($this->session, $config);

		$this->assertInstanceOf($this->factoryClass, $factory);
	}

	public function testCreateFactoryError()
	{
		$this->expectException('\InvalidArgumentException');
		$this->expectExceptionMessage('The "$container_or_config" argument must be either Container or Config.');

		new $this->factoryClass($this->session, 'something else');
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
		$search_context = m::mock('\\QATools\\QATools\\PageObject\\ISearchContext');

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
		$search_context = m::mock('\\QATools\\QATools\\PageObject\\ISearchContext');

		$this->assertSame($this->realFactory, $this->realFactory->initElements($search_context, $decorator));
	}

	public function testInitPage()
	{
		$this->expectPageUrlAnnotation('/relative-path', array('param' => 'value'));

		// Checks, that url builder/normalizer really uses base url from configuration.
		$this->annotationManager->shouldReceive('getPropertyAnnotations')->withAnyArgs()->andReturn(array());
		$this->session->shouldReceive('visit')->with('http://domain.tld/relative-path?param=value')->once();

		/** @var PageChild $page */
		$page = new $this->pageClass($this->realFactory);
		$page->open();
	}

	/**
	 * Sets expectation for a specific page url annotation and returns them.
	 *
	 * @param string|null $url    Url.
	 * @param array       $params Get params.
	 * @param boolean     $secure Secure mode.
	 *
	 * @return array
	 */
	protected function expectPageUrlAnnotation($url = null, array $params = array(), $secure = null)
	{
		$annotations = array();

		if ( isset($url) ) {
			$annotation = new PageUrlAnnotation();
			$annotation->url = $url;
			$annotation->params = $params;
			$annotation->secure = $secure;

			$annotations[] = $annotation;
		}

		$this->annotationManager->shouldReceive('getClassAnnotations')
			->with(m::any(), '@page-url')
			->andReturn($annotations);

		return $annotations;
	}

	public function testInitElementContainer()
	{
		$element_container = m::mock('\\QATools\\QATools\\PageObject\\Element\\AbstractElementContainer');
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

		$parts = explode('\\', $this->pageClass);
		$page_name = array_pop($parts);

		// Checks, that page locator really uses namespace prefixes from configuration.
		$page = $factory->getPage($page_name);
		$this->assertInstanceOf($this->pageClass, $page);
	}

	/**
	 * @dataProvider openedDataProvider
	 */
	public function testOpened($url, $matched)
	{
		/** @var Page $page */
		$page = m::mock($this->pageClass);
		$page->shouldReceive('getBrowserUrl')->once()->andReturn($url);

		$this->expectMatchUrlExactAnnotation($page, array(
			array('url' => 'http://www.domain.tld/relative'),
			array('url' => 'http://www.domain.tld/relative/path1'),
			array('url' => 'http://www.domain.tld/relative/path2'),
		));
		$this->expectMatchUrlRegexpAnnotation($page, array(
			array('regexp' => '#/absolute$#'),
		));
		$this->expectMatchUrlComponentAnnotation($page, array(
			array('path' => '/relative/path'),
		));

		$this->assertSame($matched, $this->realFactory->opened($page));
	}

	public function openedDataProvider()
	{
		return array(
			'exact matched' => array('http://www.domain.tld/relative/path1', true),
			'component matched' => array('http://www.domain.tld/relative/path', true),
			'regexp matched' => array('http://www.domain.tld/absolute', true),
			'nothing matched' => array('http://www.domain.tld/absolute/path', false),
		);
	}

	/**
	 * Sets expectation for full url match annotations and returns them.
	 *
	 * @param AnnotationManager $annotation_manager The annotation manager.
	 * @param array             $annotations_data   Url match.
	 *
	 * @return array
	 */
	protected function expectMatchUrlExactAnnotation(Page $page, $annotations_data = array())
	{
		$annotations = array();

		foreach ( $annotations_data as $annotation_params ) {
			$annotation = new MatchUrlExactAnnotation();
			$annotation->initAnnotation($annotation_params);

			$annotations[] = $annotation;
		}

		$this->annotationManager
			->shouldReceive('getClassAnnotations')
			->with($page, '@match-url-exact')
			->andReturn($annotations);

		return $annotations;
	}

	/**
	 * Sets expectation for regexp url match annotations and returns them.
	 *
	 * @param AnnotationManager $annotation_manager The annotation manager.
	 * @param array             $annotations_data   Url match.
	 *
	 * @return array
	 */
	protected function expectMatchUrlRegexpAnnotation(Page $page, $annotations_data = array())
	{
		$annotations = array();

		foreach ( $annotations_data as $annotation_params ) {
			$annotation = new MatchUrlRegexpAnnotation();
			$annotation->initAnnotation($annotation_params);

			$annotations[] = $annotation;
		}

		$this->annotationManager
			->shouldReceive('getClassAnnotations')
			->with($page, '@match-url-regexp')
			->andReturn($annotations);

		return $annotations;
	}

	/**
	 * Sets expectation for url match annotations and returns them.
	 *
	 * @param AnnotationManager $annotation_manager The annotation manager.
	 * @param array             $annotations_data   Url match.
	 *
	 * @return array
	 */
	protected function expectMatchUrlComponentAnnotation(Page $page, $annotations_data = array())
	{
		$annotations = array();

		foreach ( $annotations_data as $annotation_params ) {
			$annotation = new MatchUrlComponentAnnotation();
			$annotation->initAnnotation($annotation_params);

			$annotations[] = $annotation;
		}

		$this->annotationManager
			->shouldReceive('getClassAnnotations')
			->with($page, '@match-url-component')
			->andReturn($annotations);

		return $annotations;
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
		if ( $with_annotation_manager ) {
			$this->container['annotation_manager'] = $this->annotationManager;
		}

		if ( $mock_methods ) {
			$factory = m::mock(
				$this->factoryClass . '[' . implode(',', $mock_methods) . ']',
				array($this->session, $this->container)
			);
		}
		else {
			$factory = new $this->factoryClass($this->session, $this->container);
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
		$decorator->shouldReceive('decorate');

		return $decorator;
	}

}
