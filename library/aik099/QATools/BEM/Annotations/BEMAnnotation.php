<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM\Annotations;


use mindplay\annotations\Annotation;
use aik099\QATools\PageObject\How;

/**
 * Defines BEM meta-data.
 *
 * @usage('class'=>true, 'property'=>true, 'inherited'=>false)
 */
class BEMAnnotation extends Annotation
{

	/**
	 * Block name.
	 *
	 * @var string
	 */
	public $block;

	/**
	 * Element name.
	 *
	 * @var string
	 */
	public $element;

	/**
	 * Modifier.
	 *
	 * @var array
	 */
	public $modifier;

	/**
	 * Returns selector, that combines block, element and modifier.
	 *
	 * @return array
	 */
	public function getSelector()
	{
		$class_name = $this->block;

		if ( $this->element ) {
			$class_name .= '__' . $this->element;
		}

		if ( $this->modifier ) {
			$class_name .= '_' . key($this->modifier) . '_' . current($this->modifier);
		}

		return array('se' => array(How::CLASS_NAME => $class_name));
	}

}
