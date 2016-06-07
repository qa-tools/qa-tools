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
 * Represents web page checkbox control.
 */
class Checkbox extends LabeledElement implements ISimpleSetter
{

	/**
	 * List of acceptance criteria.
	 *
	 * @var array
	 */
	protected $acceptanceCriteria = array(
		array('tag' => 'input', 'attrs' => array('type' => 'checkbox')),
	);

	/**
	 * Checks checkbox if it is not already checked.
	 *
	 * @return self
	 */
	public function check()
	{
		$this->getWrappedElement()->check();

		return $this;
	}

	/**
	 * Unchecks checkbox if it is not already unchecked.
	 *
	 * @return self
	 */
	public function uncheck()
	{
		$this->getWrappedElement()->uncheck();

		return $this;
	}

	/**
	 * Alters checkbox checked state.
	 *
	 * @param boolean|null $check_or_uncheck Tells, how checkbox state should be altered.
	 *
	 * @return self
	 */
	public function toggle($check_or_uncheck = null)
	{
		if ( !isset($check_or_uncheck) ) {
			$check_or_uncheck = !$this->isChecked();
		}

		return $check_or_uncheck ? $this->check() : $this->uncheck();
	}

	/**
	 * Indicates whether checkbox is checked.
	 *
	 * @return boolean
	 */
	public function isChecked()
	{
		return $this->getWrappedElement()->isChecked();
	}

	/**
	 * Sets value to the element.
	 *
	 * @param mixed $value New value.
	 *
	 * @return self
	 */
	public function setValue($value)
	{
		return $this->toggle((bool)$value);
	}

}
