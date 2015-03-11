<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\ElementLocator;


use Behat\Mink\Element\NodeElement;
use mindplay\annotations\AnnotationManager;
use QATools\QATools\PageObject\Annotation\FindByAnnotation;
use QATools\QATools\PageObject\Exception\AnnotationException;
use QATools\QATools\PageObject\ISearchContext;
use QATools\QATools\PageObject\Property;

/**
 * Class, that locates WebElements.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
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
	 * Cached NodeElement list.
	 *
	 * @var NodeElement[]
	 */
	protected $cachedElements;

	/**
	 * Creates a new element locator.
	 *
	 * @param Property          $property           Property.
	 * @param ISearchContext    $search_context     The context to use when finding the element.
	 * @param AnnotationManager $annotation_manager Annotation manager.
	 */
	public function __construct(
		Property $property,
		ISearchContext $search_context,
		AnnotationManager $annotation_manager
	) {
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
		if ( isset($this->cachedElements) && $this->shouldCache ) {
			return $this->cachedElements;
		}

		$elements = array();

		foreach ( $this->getSelectors() as $selector ) {
			$elements = array_merge($elements, $this->searchContext->findAll('se', $selector));
		}

		if ( $this->shouldCache ) {
			$this->cachedElements = $elements;
		}

		return $elements;
	}

	/**
	 * Returns final selectors to be used for element locating.
	 *
	 * @return array
	 * @throws AnnotationException When required @find-by annotation is missing.
	 */
	protected function getSelectors()
	{
		/* @var $annotations FindByAnnotation[] */
		$annotations = $this->property->getAnnotationsFromPropertyOrClass('@find-by');

		$this->assertAnnotationClass($annotations);

		$selectors = array();

		foreach ( $annotations as $annotation ) {
			$selectors[] = $annotation->getSelector();
		}

		return $selectors;
	}

	/**
	 * Asserts that required annotations are present.
	 *
	 * @param array $annotations Annotations to test.
	 *
	 * @return void
	 *
	 * @throws AnnotationException Thrown if none or wrong annotations given.
	 */
	protected function assertAnnotationClass(array $annotations)
	{
		if ( !$annotations ) {
			$parameters = array((string)$this->property, $this->property->getDataType());
			$message = '@find-by must be specified in the property "%s" DocBlock or in class "%s" DocBlock';
			throw new AnnotationException(vsprintf($message, $parameters), AnnotationException::TYPE_REQUIRED);
		}

		foreach ( $annotations as $annotation ) {
			if ( !($annotation instanceof FindByAnnotation) ) {
				$parameters = array((string)$this->property, $this->property->getDataType());
				$message = '@find-by must be specified in the property "%s" DocBlock or in class "%s" DocBlock';
				throw new AnnotationException(vsprintf($message, $parameters), AnnotationException::TYPE_REQUIRED);
			}
		}
	}

	/**
	 * Returns string representation of a locator.
	 *
	 * @return string
	 */
	public function __toString()
	{
		$exported_selectors = array();

		$selectors = $this->getSelectors();

		foreach ( $selectors as $selector ) {
			$exported_selectors[] = array('se' => $selector);
		}

		return var_export($exported_selectors, true);
	}

}
