<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM\ElementLocator;


use aik099\QATools\BEM\Annotation\BEMAnnotation;
use aik099\QATools\PageObject\ElementLocator\DefaultElementLocator;
use aik099\QATools\PageObject\Exception\AnnotationException;
use aik099\QATools\PageObject\ISearchContext;
use aik099\QATools\PageObject\Property;
use mindplay\annotations\AnnotationManager;

/**
 * Locates BEM blocks/elements.
 *
 * @method \Mockery\Expectation shouldReceive
 */
class BEMElementLocator extends DefaultElementLocator
{

	/**
	 * Locator helper.
	 *
	 * @var LocatorHelper
	 */
	private $_helper;

	/**
	 * Creates a new element locator.
	 *
	 * @param Property          $property           Property.
	 * @param ISearchContext    $search_context     The context to use when finding the element.
	 * @param AnnotationManager $annotation_manager Annotation manager.
	 * @param LocatorHelper     $locator_helper     Locator helper.
	 */
	public function __construct(
		Property $property,
		ISearchContext $search_context,
		AnnotationManager $annotation_manager,
		LocatorHelper $locator_helper
	)
	{
		parent::__construct($property, $search_context, $annotation_manager);

		$this->_helper = $locator_helper;
	}

	/**
	 * Returns block locator.
	 *
	 * @param string      $block_name        Block name.
	 * @param string|null $modificator_name  Modificator name.
	 * @param string|null $modificator_value Modificator value.
	 *
	 * @return array
	 */
	public function getBlockLocator($block_name, $modificator_name = null, $modificator_value = null)
	{
		return $this->_helper->getBlockLocator($block_name, $modificator_name, $modificator_value);
	}

	/**
	 * Returns element locator.
	 *
	 * @param string      $element_name      Element name.
	 * @param string      $block_name        Block name.
	 * @param string|null $modificator_name  Modificator name.
	 * @param string|null $modificator_value Modificator value.
	 *
	 * @return array
	 */
	public function getElementLocator($element_name, $block_name, $modificator_name = null, $modificator_value = null)
	{
		return $this->_helper->getElementLocator($element_name, $block_name, $modificator_name, $modificator_value);
	}

	/**
	 * Returns final selector to be used for element locating.
	 *
	 * @return array
	 * @throws AnnotationException When required @bem annotation is missing.
	 */
	protected function getSelector()
	{
		/* @var $annotations BEMAnnotation[] */
		$annotations = $this->property->getAnnotations('@bem');

		if ( !$annotations ) {
			throw new AnnotationException(
				'BEM block/element must be specified as annotation',
				AnnotationException::TYPE_REQUIRED
			);
		}

		$bem_annotation = $annotations[0];

		if ( $bem_annotation->element ) {
			$bem_annotation->block = $this->searchContext->getName();
		}

		return $bem_annotation->getSelector($this->_helper);
	}

}
