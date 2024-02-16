<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject;


use QATools\QATools\PageObject\Annotation\PageUrlAnnotation;
use QATools\QATools\PageObject\Config\Config;
use QATools\QATools\PageObject\Config\IConfig;
use QATools\QATools\PageObject\Element\IElementContainer;
use QATools\QATools\PageObject\ElementLocator\DefaultElementLocatorFactory;
use QATools\QATools\PageObject\PageLocator\IPageLocator;
use QATools\QATools\PageObject\PageUrlMatcher\PageUrlMatcherRegistry;
use QATools\QATools\PageObject\PropertyDecorator\DefaultPropertyDecorator;
use QATools\QATools\PageObject\PropertyDecorator\IPropertyDecorator;
use QATools\QATools\PageObject\Url\IUrlFactory;
use QATools\QATools\PageObject\Url\Normalizer;
use Behat\Mink\Session;
use mindplay\annotations\AnnotationManager;

/**
 * Factory class to make using Page Objects simpler and easier.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
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
		'find-by' => '\\QATools\\QATools\\PageObject\\Annotation\\FindByAnnotation',
		'page-url' => '\\QATools\\QATools\\PageObject\\Annotation\\PageUrlAnnotation',
		'timeout' => '\\QATools\\QATools\\PageObject\\Annotation\\TimeoutAnnotation',
		'method' => false,
	);

	/**
	 * The url builder factory.
	 *
	 * @var IUrlFactory
	 */
	protected $urlFactory;

	/**
	 * The url normalizer.
	 *
	 * @var Normalizer
	 */
	protected $urlNormalizer;

	/**
	 * The page locator.
	 *
	 * @var IPageLocator
	 */
	protected $pageLocator;

	/**
	 * The page url matcher registry.
	 *
	 * @var PageUrlMatcherRegistry
	 */
	protected $pageUrlMatcherRegistry;

	/**
	 * The current config.
	 *
	 * @var IConfig
	 */
	protected $config;

	/**
	 * Creates PageFactory instance.
	 *
	 * @param Session               $session             Mink session.
	 * @param Container|Config|null $container_or_config Dependency injection container_or_config or Config.
	 *
	 * @throws \InvalidArgumentException When something else was given instead of container_or_config/config object.
	 */
	public function __construct(Session $session, $container_or_config = null)
	{
		if ( isset($container_or_config) ) {
			if ( $container_or_config instanceof Config ) {
				$container_or_config = $this->_createContainer($container_or_config);
			}
			elseif ( !($container_or_config instanceof Container) ) {
				throw new \InvalidArgumentException(
					'The "$container_or_config" argument must be either Container or Config.'
				);
			}
		}
		else {
			$container_or_config = $this->_createContainer();
		}

		$this->_setSession($session);
		$this->config = $container_or_config['config'];

		$this->_setAnnotationManager($container_or_config['annotation_manager']);
		$this->urlFactory = $container_or_config['url_factory'];
		$this->urlNormalizer = $container_or_config['url_normalizer'];
		$this->pageLocator = $container_or_config['page_locator'];
		$this->pageUrlMatcherRegistry = $container_or_config['page_url_matcher_registry'];
	}

	/**
	 * Creates container_or_config object.
	 *
	 * @param Config|null $config Config.
	 *
	 * @return Container
	 */
	private function _createContainer(Config $config = null)
	{
		$container = new Container();

		if ( isset($config) ) {
			$container['config'] = $config;
		}

		return $container;
	}

	/**
	 * Sets annotation manager.
	 *
	 * @param AnnotationManager $manager Annotation manager.
	 *
	 * @return self
	 */
	private function _setAnnotationManager(AnnotationManager $manager)
	{
		foreach ( $this->annotationRegistry as $annotation_name => $annotation_class ) {
			$manager->registry[$annotation_name] = $annotation_class;
		}

		$this->annotationManager = $manager;

		return $this;
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
	 * Sets session.
	 *
	 * @param Session $session Session.
	 *
	 * @return self
	 */
	private function _setSession(Session $session)
	{
		$selectors_handler = $session->getSelectorsHandler();

		if ( !$selectors_handler->isSelectorRegistered('se') ) {
			$selectors_handler->registerSelector('se', new SeleniumSelector());
		}

		$this->_session = $session;

		return $this;
	}

	/**
	 * Returns session.
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

		if ( !$annotations || !($annotations[0] instanceof PageUrlAnnotation) ) {
			return $this;
		}

		$page->setUrlBuilder(
			$this->urlFactory->getBuilder(
				$this->urlNormalizer->normalize($annotations[0])
			)
		);

		return $this;
	}

	/**
	 * Checks if the given page is currently opened in browser.
	 *
	 * @param Page $page Page to check.
	 *
	 * @return boolean
	 */
	public function opened(Page $page)
	{
		// Changing "match" method signature would introduce a BC break.
		return $this->pageUrlMatcherRegistry->match($page->getCurrentUrl(), $page);
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
		$resolved_page_class = $this->pageLocator->resolvePage($class_name);

		return new $resolved_page_class($this);
	}

}
