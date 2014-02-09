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
 * Represents a group of radio buttons.
 */
class Radio extends LabeledElement
{

	/**
	 * Selects radio button uf it's not already selected.
	 *
	 * @return self
	 */
	public function select()
	{
		$this->getWrappedElement()->check();

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

}
