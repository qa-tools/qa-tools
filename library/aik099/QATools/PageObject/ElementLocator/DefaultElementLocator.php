<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\ElementLocator;


use aik099\QATools\PageObject\Exception\AnnotationException;
use Behat\Mink\Element\NodeElement;
use mindplay\annotations\AnnotationManager;
use aik099\QATools\PageObject\Annotation\FindByAnnotation;
use aik099\QATools\PageObject\ISearchContext;
use aik099\QATools\PageObject\Property;

/**
 * Class, that locates WebElements.
 *
 * @method \Mockery\Expectation shouldReceive()
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
	 * Creates a new element locator.
	 *
	 * @param Property          $property           Property.
	 * @param ISearchContext    $search_context     The context to use when finding the element.
	 * @param AnnotationManager $annotation_manager Annotation manager.
	 */
	public function __construct(Property $property, ISearchContext $search_context, AnnotationManager $annotation_manager)
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
		return $this->searchContext->findAll('se', $this->getSelector());
	}

	/**
	 * Returns final selector to be used for element locating.
	 *
	 * @return array
	 * @throws AnnotationException When required @find-by annotation is missing.
	 */
	protected function getSelector()
	{
		/* @var $annotations FindByAnnotation[] */
		$annotations = $this->property->getAnnotationsFromPropertyOrClass('@find-by');
		$selector = $annotations ? $annotations[0]->getSelector() : array();

		if ( !$selector ) {
			$parameters = array((string)$this->property, $this->property->getDataType());
			$message = '@find-by must be specified in the property "%s" DocBlock or in class "%s" DocBlock';

			throw new AnnotationException(vsprintf($message, $parameters), AnnotationException::TYPE_REQUIRED);
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
		return var_export(array('se' => $this->getSelector()), true);
	}

}
