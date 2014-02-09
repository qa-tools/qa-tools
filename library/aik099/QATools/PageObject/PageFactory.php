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


use Behat\Mink\Session;
use mindplay\annotations\AnnotationCache;
use mindplay\annotations\AnnotationManager;
use aik099\QATools\PageObject\Annotation\PageUrlAnnotation;
use aik099\QATools\PageObject\ElementLocator\DefaultElementLocatorFactory;
use aik099\QATools\PageObject\Element\IHtmlElement;
use aik099\QATools\PageObject\Exception\PageFactoryException;
use aik099\QATools\PageObject\PropertyDecorator\DefaultPropertyDecorator;
use aik099\QATools\PageObject\PropertyDecorator\IPropertyDecorator;

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
		'method' => false,
	);

	/**
	 * Creates PageFactory instance.
	 *
	 * @param Session                $session            Mink session.
	 * @param AnnotationManager|null $annotation_manager Annotation manager.
	 */
	public function __construct(Session $session, AnnotationManager $annotation_manager = null)
	{
		$this->_session = $session;

		if ( !isset($annotation_manager) ) {
			$annotation_manager = new AnnotationManager();
			$annotation_manager->cache = new AnnotationCache(sys_get_temp_dir());
		}

		$this->setAnnotationManager($annotation_manager)->attachSeleniumSelector();
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
		foreach ($this->annotationRegistry as $annotation_name => $annotation_class) {
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
			$page->relativeUrl = $annotations[0]->url;
		}

		return $this;
	}

	/**
	 * Initializes HtmlElement.
	 *
	 * @param IHtmlElement $html_element HtmlElement to be initialized.
	 *
	 * @return self
	 */
	public function initHtmlElement(IHtmlElement $html_element)
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
	 * @throws PageFactoryException When class of non-existing element discovered in property's @var annotation.
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

		foreach ($reflection->getProperties() as $property) {
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
