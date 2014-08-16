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
use QATools\QATools\PageObject\Annotation\TimeoutAnnotation;
use QATools\QATools\PageObject\ISearchContext;
use QATools\QATools\PageObject\Property;

/**
 * Class, that locates WebElements that might not be present at the moment.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class WaitingElementLocator extends DefaultElementLocator
{

	/**
	 * Time to wait for element to be ready (in seconds).
	 *
	 * @var integer
	 */
	protected $timeout = 0;

	/**
	 * Creates a new element locator.
	 *
	 * @param Property          $property           Property.
	 * @param ISearchContext    $search_context     The context to use when finding the element.
	 * @param AnnotationManager $annotation_manager Annotation manager.
	 */
	public function __construct(Property $property, ISearchContext $search_context, AnnotationManager $annotation_manager)
	{
		parent::__construct($property, $search_context, $annotation_manager);

		/** @var TimeoutAnnotation[] $annotations */
		$annotations = $property->getAnnotationsFromPropertyOrClass('@timeout');

		if ( $annotations ) {
			$this->timeout = $annotations[0]->duration;
		}
	}

	/**
	 * Find the element list.
	 *
	 * @return NodeElement[]
	 */
	public function findAll()
	{
		if ( $this->timeout == 0 ) {
			return parent::findAll();
		}

		return $this->searchContext->waitFor($this->timeout, array($this, 'parent::findAll'));
	}

}
