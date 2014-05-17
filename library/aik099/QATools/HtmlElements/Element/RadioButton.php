<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\HtmlElements\Element;


/**
 * Represents single radio button in radio button group. Please use "RadioGroup" typified element for manipulations.
 */
class RadioButton extends LabeledElement
{

	/**
	 * Selects radio button uf it's not already selected.
	 *
	 * @return self
	 */
	public function select()
	{
		$this->getWrappedElement()->selectOption($this->getValue());

		return $this;
	}

	/**
	 * Indicates whether radio button is selected.
	 *
	 * @return boolean
	 */
	public function isSelected()
	{
		return $this->getWrappedElement()->isChecked();
	}

	/**
	 * Returns value of an option.
	 *
	 * @return string
	 */
	public function getValue()
	{
		return $this->getAttribute('value');
	}

}
