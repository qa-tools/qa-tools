<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\Annotation;


use mindplay\annotations\Annotation;

/**
 * Defines element loading timeout.
 *
 * @usage('class'=>true, 'property'=>true, 'inherited'=>true)
 */
class TimeoutAnnotation extends Annotation
{

	/**
	 * Time to wait for element to be present (in milliseconds).
	 *
	 * @var string
	 */
	public $duration;

	/**
	 * Initialize the annotation.
	 *
	 * @param array $properties Annotation parameters.
	 *
	 * @return void
	 */
	public function initAnnotation(array $properties)
	{
		$this->map($properties, array('duration'));

		parent::initAnnotation($properties);
	}

}
