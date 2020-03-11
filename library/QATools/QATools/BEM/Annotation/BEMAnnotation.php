<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\BEM\Annotation;


use QATools\QATools\BEM\ElementLocator\LocatorHelper;
use mindplay\annotations\Annotation;

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
	 * Modificator.
	 *
	 * @var array
	 */
	public $modificator;

	/**
	 * Returns selector, that combines block, element and modificator.
	 *
	 * @param LocatorHelper $locator_helper Locator helper.
	 *
	 * @return array
	 */
	public function getSelector(LocatorHelper $locator_helper)
	{
		list($modificator_name, $modificator_value) = $this->getModificator();

		if ( $this->element ) {
			return $locator_helper->getElementLocator(
				$this->element,
				$this->block,
				$modificator_name,
				$modificator_value
			);
		}

		return $locator_helper->getBlockLocator($this->block, $modificator_name, $modificator_value);
	}

	/**
	 * Returns modificator.
	 *
	 * @return array
	 */
	protected function getModificator()
	{
		if ( $this->modificator ) {
			$modificator_name = key($this->modificator);
			$modificator_value = $this->modificator[$modificator_name];
		}
		else {
			$modificator_name = $modificator_value = null;
		}

		return array($modificator_name, $modificator_value);
	}

}
