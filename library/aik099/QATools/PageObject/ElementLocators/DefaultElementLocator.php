<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\ElementLocators;


use Behat\Mink\Element\NodeElement;
use mindplay\annotations\AnnotationManager;
use aik099\QATools\PageObject\Annotations\FindByAnnotation;
use aik099\QATools\PageObject\Elements\IWebElement;
use aik099\QATools\PageObject\Exceptions\PageFactoryException;
use aik099\QATools\PageObject\ISearchContext;
use aik099\QATools\PageObject\Property;

/**
 * Class, that locates WebElements.
 *
 * @method \Mockery\Expectation shouldReceive
 */
class DefaultElementLocator implements IElementLocator
{

	/**
	 * Search context.
	 *
	 * @var ISearchContext
	 */
	protected $searchContext;

	/**
	 * Annotation manager.
	 *
	 * @var AnnotationManager
	 */
	protected $annotationManager;

	/**
	 * Property.
	 *
	 * @var Property
	 */
	protected $property;

	/**
	 * Cache found elements locally.
	 *
	 * @var boolean
	 */
	protected $shouldCache = false;

	/**
	 * Cached WebElement list.
	 *
	 * @var IWebElement[]
	 */
	protected $cachedElements;

	/**
	 * Creates a new element locator.
	 *
	 * @param Property          $property           Property.
	 * @param AnnotationManager $annotation_manager Annotation manager.
	 * @param ISearchContext    $search_context     The context to use when finding the element.
	 */
	public function __construct(Property $property, AnnotationManager $annotation_manager, ISearchContext $search_context)
	{
		$this->property = $property;
		$this->searchContext = $search_context;
		$this->annotationManager = $annotation_manager;
	}

	/**
	 * Returns search context in use.
	 *
	 * @return ISearchContext
	 */
	public function getSearchContext()
	{
		return $this->searchContext;
	}

	/**
	 * Find the element.
	 *
	 * @return NodeElement|null
	 */
	public function find()
	{
		$items = $this->findAll();

		return count($items) ? current($items) : null;
	}

	/**
	 * Find the element list.
	 *
	 * @return NodeElement[]
	 */
	public function findAll()
	{
		/*if ( $this->cachedElements != null && $this->shouldCache ) {
			return $this->cachedElements;
		}*/

		$elements = $this->searchContext->findAll('se', $this->getSelector($this->property));

		/*if ( $this->shouldCache ) {
			$this->cachedElements = $elements;
		}*/

		return $elements;
	}

	/**
	 * Returns final selector to be used for element locating.
	 *
	 * @param Property $property Property.
	 *
	 * @return array
	 * @throws PageFactoryException When required @find-by annotation is missing.
	 */
	protected function getSelector(Property $property)
	{
		/* @var $annotations FindByAnnotation[] */
		$annotations = $property->getAnnotationsFromPropertyOrClass('@find-by');
		$selector = $annotations ? $annotations[0]->getSelector() : array();

		if ( !$selector ) {
			$parameters = array((string)$property, $property->getDataType());
			$message = '@find-by must be specified in the property "%s" DocBlock or in class "%s" DocBlock';

			throw new PageFactoryException(vsprintf($message, $parameters));
		}

		return $selector;
	}

	/**
	 * Returns string representation of a locator.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return var_export(array('se' => $this->getSelector($this->property)), true);
	}

}
