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


use aik099\QATools\BEM\Exception\ElementException;
use aik099\QATools\PageObject\How;

class LocatorHelper
{

	/**
	 * Returns block locator.
	 *
	 * @param string      $block_name        Block name.
	 * @param string|null $modificator_name  Modificator name.
	 * @param string|null $modificator_value Modificator value.
	 *
	 * @return array
	 * @throws ElementException When block isn't specified.
	 */
	public function getBlockLocator($block_name, $modificator_name = null, $modificator_value = null)
	{
		if ( !$block_name ) {
			throw new ElementException('BEM Block name cannot be empty', ElementException::TYPE_BLOCK_REQUIRED);
		}

		$class_name = $block_name . $this->getModificatorSelector($modificator_name, $modificator_value);

		return array(How::CLASS_NAME => $class_name);
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
	 * @throws ElementException When block/element isn't specified.
	 */
	public function getElementLocator($element_name, $block_name, $modificator_name = null, $modificator_value = null)
	{
		if ( !$element_name ) {
			throw new ElementException('BEM element name cannot be empty', ElementException::TYPE_ELEMENT_REQUIRED);
		}

		if ( !$block_name ) {
			throw new ElementException('BEM block name cannot be empty', ElementException::TYPE_BLOCK_REQUIRED);
		}

		$class_name = $block_name . '__' . $element_name . $this->getModificatorSelector($modificator_name, $modificator_value);

		return array(How::CLASS_NAME => $class_name);
	}

	/**
	 * Returns block/element modificator selector.
	 *
	 * @param string|null $modificator_name  Modificator name.
	 * @param string|null $modificator_value Modificator value.
	 *
	 * @return string
	 */
	protected function getModificatorSelector($modificator_name = null, $modificator_value = null)
	{
		if ( isset($modificator_name) && isset($modificator_value) ) {
			return '_' . $modificator_name . '_' . $modificator_value;
		}

		return '';
	}

}
