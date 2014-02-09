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
use aik099\QATools\BEM\Element\IBlock;
use aik099\QATools\BEM\Exception\BEMPageFactoryException;
use aik099\QATools\PageObject\ElementLocator\DefaultElementLocator;
use aik099\QATools\PageObject\Property;

/**
 * Locates BEM blocks/elements.
 *
 * @method \Mockery\Expectation shouldReceive
 */
class BEMElementLocator extends DefaultElementLocator
{

	/**
	 * Returns final selector to be used for element locating.
	 *
	 * @param Property $property Property.
	 *
	 * @return array
	 * @throws BEMPageFactoryException When required @find-by annotation is missing.
	 */
	protected function getSelector(Property $property)
	{
		/* @var $annotations BEMAnnotation[] */
		$annotations = $property->getAnnotations('@bem');

		if ( !$annotations ) {
			throw new BEMPageFactoryException('Block/Element must be defined as annotation');
		}

		$bem_annotation = $annotations[0];

		if ( !$bem_annotation->block && $this->searchContext instanceof IBlock ) {
			$bem_annotation->block = $this->searchContext->getName();
		}

		return $bem_annotation->getSelector();
	}

}
