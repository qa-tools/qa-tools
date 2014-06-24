<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace aik099\QATools\HtmlElements\Annotation;


use mindplay\annotations\Annotation;

/**
 * Defines typified element name metadata.
 *
 * @usage('class'=>true, 'property'=>true, 'inherited'=>true)
 */
class ElementNameAnnotation extends Annotation
{

	/**
	 * Name of typified element.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Initialize the annotation.
	 *
	 * @param array $properties Annotation parameters.
	 *
	 * @return void
	 */
	public function initAnnotation(array $properties)
	{
		$this->map($properties, array('name'));

		parent::initAnnotation($properties);
	}

}
