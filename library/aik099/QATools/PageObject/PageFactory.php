<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject;


use aik099\QATools\PageObject\Annotation\PageUrlAnnotation;
use aik099\QATools\PageObject\Config\Config;
use aik099\QATools\PageObject\Config\IConfig;
use aik099\QATools\PageObject\Element\IElementContainer;
use aik099\QATools\PageObject\ElementLocator\DefaultElementLocatorFactory;
use aik099\QATools\PageObject\PropertyDecorator\DefaultPropertyDecorator;
use aik099\QATools\PageObject\PropertyDecorator\IPropertyDecorator;
use aik099\QATools\PageObject\Url\IUrlBuilderFactory;
use aik099\QATools\PageObject\Url\UrlBuilderFactory;
use Behat\Mink\Session;
use mindplay\annotations\AnnotationCache;
use mindplay\annotations\AnnotationManager;

/**
 * Factory class to make using Page Objects simpler and easier.
 *
 * @method \Mockery\Expectation shouldReceive
 */
class PageFactory implements IPageFactory
{

	/**
	 * Instance of Mink session.
	 *
	 * @var Session
	 */
	private $_session;

	/**
	 * Annotation manager.
	 *
	 * @var AnnotationManager
	 */
	protected $annotationManager;

	/**
	 * Supported annotation class map.
	 *
	 * @var array
	 */
	protected $annotationRegistry = array(
		'find-by' => '\\aik099\\QATools\\PageObject\\Annotation\\FindByAnnotation',
		'page-url' => '\\aik099\\QATools\\PageObject\\Annotation\\PageUrlAnnotation',
		'timeout' => '\\aik099\\QATools\\PageObject\\Annotation\\TimeoutAnnotation',
		'method' => false,
	);

	/**
	 * The url builder factory.
	 *
	 * @var IUrlBuilderFactory
	 */
	protected $urlBuilderFactory;

	/**
	 * The current config.
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 * Creates PageFactory instance.
	 *
	 * @param Session                $session            Mink session.
	 * @param IConfig                $config             Page factory configuration.
	 * @param AnnotationManager|null $annotation_manager Annotation manager.
	 */
	public function __construct(Session $session, IConfig $config = null, AnnotationManager $annotation_manager = null)
	{
		$this->_session = $session;

		if ( !isset($annotation_manager) ) {
			$annotation_manager = new AnnotationManager();
			$annotation_manager->cache = new AnnotationCache(sys_get_temp_dir());
		}

		$this->config = isset($config) ? $config : new Config();
		$this->setAnnotationManager($annotation_manager)->attachSeleniumSelector();
		$this->setUrlBuilderFactory(new UrlBuilderFactory());
	}

	/**
	 * Attaches Selenium selector, that is later used during annotation processing.
	 *
	 * @return self
	 */
	protected function attachSeleniumSelector()
	{
		$selectors_handler = $this->_session->getSelectorsHandler();

		if ( !$selectors_handler->isSelectorRegistered('se') ) {
			$selectors_handler->registerSelector('se', new SeleniumSelector($selectors_handler));
		}

		return $this;
	}

	/**
	 * Sets annotation manager.
	 *
	 * @param AnnotationManager $manager Annotation manager.
	 *
	 * @return self
	 */
	public function setAnnotationManager(AnnotationManager $manager)
	{
		foreach ( $this->annotationRegistry as $annotation_name => $annotation_class ) {
			$manager->registry[$annotation_name] = $annotation_class;
		}

		$this->annotationManager = $manager;

		return $this;
	}

	/**
	 * Returns annotation manager.
	 *
	 * @return AnnotationManager
	 */
	public function getAnnotationManager()
	{
		return $this->annotationManager;
	}

	/**
	 * Sets the url builder factory.
	 *
	 * @param IUrlBuilderFactory $url_builder_factory Url builder factory.
	 *
	 * @return IUrlBuilderFactory
	 */
	public function setUrlBuilderFactory(IUrlBuilderFactory $url_builder_factory)
	{
		$this->urlBuilderFactory = $url_builder_factory;

		return $this;
	}

	/**
	 * Returns current url builder factory.
	 *
	 * @return IUrlBuilderFactory
	 */
	public function getUrlBuilderFactory()
	{
		return $this->urlBuilderFactory;
	}

	/**
	 * Creates default decorator.
	 *
	 * @param ISearchContext $search_context Search context.
	 *
	 * @return IPropertyDecorator
	 */
	public function createDecorator(ISearchContext $search_context)
	{
		$locator_factory = new DefaultElementLocatorFactory($search_context, $this->annotationManager);

		return new DefaultPropertyDecorator($locator_factory, $this);
	}

	/**
	 * Returns element session.
	 *
	 * @return Session
	 */
	public function getSession()
	{
		return $this->_session;
	}

	/**
	 * Initializes the page.
	 *
	 * @param Page $page Page to initialize.
	 *
	 * @return self
	 */
	public function initPage(Page $page)
	{
		/* @var $annotations PageUrlAnnotation[] */
		$annotations = $this->annotationManager->getClassAnnotations($page, '@page-url');

		if ( $annotations ) {
			$page->setUrlBuilder($this->urlBuilderFactory->getUrlBuilder($annotations[0]->url, $annotations[0]->params));
		}

		return $this;
	}

	/**
	 * Initializes AbstractElementContainer.
	 *
	 * @param IElementContainer $element_container AbstractElementContainer to be initialized.
	 *
	 * @return self
	 */
	public function initElementContainer(IElementContainer $element_container)
	{
		return $this;
	}

	/**
	 * Initializes elements in given search context.
	 *
	 * @param ISearchContext     $search_context     Context, to be used for element initialization.
	 * @param IPropertyDecorator $property_decorator Element locator factory.
	 *
	 * @return self
	 */
	public function initElements(ISearchContext $search_context, IPropertyDecorator $property_decorator)
	{
		return $this->proxyFields($search_context, $property_decorator);
	}

	/**
	 * Initializes fields within given search context.
	 *
	 * @param ISearchContext     $search_context     Search context.
	 * @param IPropertyDecorator $property_decorator Property decorator.
	 *
	 * @return self
	 */
	protected function proxyFields(ISearchContext $search_context, IPropertyDecorator $property_decorator)
	{
		foreach ( $this->getProperties($search_context) as $property ) {
			$proxy = $property_decorator->decorate($property);

			if ( $proxy !== null ) {
				$property->setAccessible(true);
				$property->setValue($search_context, $proxy);
			}
		}

		return $this;
	}

	/**
	 * Returns class properties, that can potentially become proxies.
	 *
	 * @param ISearchContext $search_context Search context.
	 *
	 * @return Property[]
	 */
	protected function getProperties(ISearchContext $search_context)
	{
		$ret = array();
		$reflection = new \ReflectionClass($search_context);

		foreach ( $reflection->getProperties() as $property ) {
			$ret[] = new Property($property, $this->annotationManager);
		}

		return $ret;
	}

	/**
	 * Creates page by given class name.
	 *
	 * @param string $class_name Page class name.
	 *
	 * @return Page
	 */
	public function getPage($class_name)
	{
		$reflection = new \ReflectionClass($class_name);

		return $reflection->newInstanceArgs(array($this));
	}

}
