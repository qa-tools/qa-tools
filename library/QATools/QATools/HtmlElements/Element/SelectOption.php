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


use QATools\QATools\HtmlElements\Exception\SelectException;

/**
 * Represents one option in a web page select control. Please use "Select" typified element for manipulations.
 */
class SelectOption extends AbstractFormElement
{

	/**
	 * SELECT element.
	 *
	 * @var Select
	 */
	protected $select;

	/**
	 * List of acceptance criteria.
	 *
	 * @var array
	 */
	protected $acceptanceCriteria = array(
		array('tag' => 'option'),
	);

	/**
	 * Sets reference to parent SELECT element.
	 *
	 * @param Select $select Associated SELECT element.
	 *
	 * @return self
	 */
	public function setSelect(Select $select)
	{
		$this->select = $select;

		return $this;
	}

	/**
	 * Selects option if it is not already selected.
	 *
	 * @param boolean $multiple Append this option to current selection.
	 *
	 * @return self
	 * @throws SelectException When no SELECT element association defined.
	 */
	public function select($multiple = false)
	{
		if ( !$this->isSelected() ) {
			if ( $this->select === null ) {
				throw new SelectException(
					'No SELECT element association defined',
					SelectException::TYPE_UNBOUND_OPTION
				);
			}

			$this->select->getWrappedElement()->selectOption($this->getValue(), $multiple);
		}

		return $this;
	}

	/**
	 * Deselects option if it is not already deselected.
	 *
	 * @return self
	 * @throws SelectException When non-Selenium driver is used.
	 */
	public function deselect()
	{
		if ( $this->isSelected() ) {
			if ( !$this->isSeleniumDriver() ) {
				throw new SelectException(
					'Deselecting individual options is only supported in Selenium drivers',
					SelectException::TYPE_NOT_SUPPORTED
				);
			}

			$this->getWrappedElement()->click();
		}

		return $this;
	}

	/**
	 * Alters option selected state.
	 *
	 * @param boolean|null $select_or_deselect Tells, how option state should be altered.
	 *
	 * @return self
	 */
	public function toggle($select_or_deselect = null)
	{
		if ( !isset($select_or_deselect) ) {
			$select_or_deselect = !$this->isSelected();
		}

		return $select_or_deselect ? $this->select() : $this->deselect();
	}

	/**
	 * Indicates whether checkbox is checked.
	 *
	 * @return boolean
	 */
	public function isSelected()
	{
		return $this->getWrappedElement()->isSelected();
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

	/**
	 * Returns text of an option.
	 *
	 * @return string
	 */
	public function getText()
	{
		return $this->getWrappedElement()->getText();
	}

}
