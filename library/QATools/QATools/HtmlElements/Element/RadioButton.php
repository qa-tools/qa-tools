<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\HtmlElements\Element;


/**
 * Represents single radio button in radio button group. Please use "RadioGroup" typified element for manipulations.
 */
class RadioButton extends LabeledElement
{

	/**
	 * List of acceptance criteria.
	 *
	 * @var array
	 */
	protected $acceptanceCriteria = array(
		array('tag' => 'input', 'attrs' => array('type' => 'radio')),
	);

	/**
	 * Selects radio button uf it's not already selected.
	 *
	 * @return static
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
