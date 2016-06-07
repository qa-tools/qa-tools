<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\Annotation;


use mindplay\annotations\Annotation;

/**
 * Defines element loading timeout.
 *
 * @usage('class'=>true, 'property'=>true, 'inherited'=>true)
 */
class TimeoutAnnotation extends Annotation
{

	/**
	 * Time to wait for element to be present (in seconds).
	 *
	 * @var integer
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
