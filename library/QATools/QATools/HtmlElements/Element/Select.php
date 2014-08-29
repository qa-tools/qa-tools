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
use Behat\Mink\Element\NodeElement;
use QATools\QATools\PageObject\How;

/**
 * Represents web page select control.
 */
class Select extends AbstractFormElement implements ISimpleSetter
{

	/**
	 * Indicates whether this select element support selecting multiple options at the same time.
	 *
	 * @return boolean
	 */
	public function isMultiple()
	{
		return $this->hasAttribute('multiple');
	}

	/**
	 * Returns all options belonging to this select tag.
	 *
	 * @return SelectOption[]
	 */
	public function getOptions()
	{
		return $this->wrapOptions($this->getWrappedElement()->findAll('se', array(How::TAG_NAME => 'option')));
	}

	/**
	 * Returns all options having given value.
	 *
	 * @param mixed $value Value of option to selected.
	 *
	 * @return SelectOption[]
	 */
	public function getOptionsByValue($value)
	{
		$selectors_handler = $this->getSelectorsHandler();
		$xpath = 'descendant-or-self::option[@value = ' . $selectors_handler->xpathLiteral($value) . ']';

		return $this->wrapOptions($this->getWrappedElement()->findAll('xpath', $xpath));
	}

	/**
	 * Returns all options that display text matching the argument.
	 *
	 * @param string  $text        Text of the option to be selected.
	 * @param boolean $exact_match Search for exact text.
	 *
	 * @return SelectOption[]
	 */
	public function getOptionsByText($text, $exact_match = true)
	{
		$selectors_handler = $this->getSelectorsHandler();

		if ( $exact_match ) {
			$xpath = 'descendant-or-self::option[normalize-space(.) = ' . $selectors_handler->xpathLiteral($text) . ']';
		}
		else {
			$xpath = 'descendant-or-self::option[contains(., ' . $selectors_handler->xpathLiteral($text) . ')]';
		}

		return $this->wrapOptions($this->getWrappedElement()->findAll('xpath', $xpath));
	}

	/**
	 * Returns all selected options belonging to this select tag.
	 *
	 * @return SelectOption[]
	 */
	public function getSelectedOptions()
	{
		$ret = array();

		foreach ( $this->getOptions() as $option ) {
			if ( $option->isSelected() ) {
				$ret[] = $option;
			}
		}

		return $ret;
	}

	/**
	 * The first selected option in this select tag (or the currently selected option in a normal select).
	 *
	 * @return SelectOption
	 * @throws SelectException When no options were selected.
	 */
	public function getFirstSelectedOption()
	{
		foreach ( $this->getOptions() as $option ) {
			if ( $option->isSelected() ) {
				return $option;
			}
		}

		throw new SelectException('No options are selected', SelectException::TYPE_NOT_SELECTED);
	}

	/**
	 * Select all options that display text matching the argument.
	 *
	 * @param string  $text        The visible text to match against.
	 * @param boolean $exact_match Search for exact text.
	 *
	 * @return self
	 * @throws SelectException No options were found by given text.
	 */
	public function selectByText($text, $exact_match = true)
	{
		$options = $this->getOptionsByText($text, $exact_match);

		if ( !$options ) {
			throw new SelectException('Cannot locate option with text: ' . $text, SelectException::TYPE_NOT_FOUND);
		}

		return $this->selectOptions($options, !$this->isMultiple());
	}

	/**
	 * Deselect all options that display text matching the argument.
	 *
	 * @param string  $text        The visible text to match against.
	 * @param boolean $exact_match Search for exact text.
	 *
	 * @return self
	 */
	public function deselectByText($text, $exact_match = true)
	{
		foreach ( $this->getOptionsByText($text, $exact_match) as $option ) {
			$option->deselect();
		}

		return $this;
	}

	/**
	 * Select all options that have a value matching the argument.
	 *
	 * @param mixed $value The value to match against.
	 *
	 * @return self
	 * @throws SelectException When option with given value can't be found.
	 */
	public function selectByValue($value)
	{
		$options = $this->getOptionsByValue($value);

		if ( !$options ) {
			throw new SelectException('Cannot locate option with value: ' . $value, SelectException::TYPE_NOT_FOUND);
		}

		return $this->selectOptions($options, !$this->isMultiple());
	}

	/**
	 * Deselect all options that have a value matching the argument.
	 *
	 * @param mixed $value Value of an option be be deselected.
	 *
	 * @return self
	 */
	public function deselectByValue($value)
	{
		foreach ( $this->getOptionsByValue($value) as $option ) {
			$option->deselect();
		}

		return $this;
	}

	/**
	 * Selects all options.
	 *
	 * @return self
	 */
	public function selectAll()
	{
		$this->assertMultiSelect();

		return $this->selectOptions($this->getOptions());
	}

	/**
	 * Replaces current selection with given one.
	 *
	 * @param array $values Values of options to select.
	 *
	 * @return self
	 */
	public function setSelected(array $values)
	{
		$this->assertMultiSelect();

		$candidates = array();
		/* @var $candidates SelectOption[] */

		foreach ( $this->getOptions() as $option ) {
			if ( in_array($option->getValue(), $values) ) {
				$candidates[] = $option;
			}
		}

		return $this->selectOptions($candidates);
	}

	/**
	 * Deselects all options.
	 *
	 * @return self
	 */
	public function deselectAll()
	{
		$this->assertMultiSelect();

		foreach ( $this->getSelectedOptions() as $option ) {
			$option->deselect();
		}

		return $this;
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
		if ( is_array($value) ) {
			return $this->setSelected($value);
		}

		return $this->selectByValue((string)$value);
	}

	/**
	 * Select given options.
	 *
	 * @param array|SelectOption[] $options    Options to be selected.
	 * @param boolean              $first_only Select only first option.
	 *
	 * @return self
	 */
	protected function selectOptions(array $options, $first_only = false)
	{
		foreach ( $options as $index => $option ) {
			$option->select($index > 0);

			if ( $first_only ) {
				return $this;
			}
		}

		return $this;
	}

	/**
	 * Wraps each of NodeElement in array with a SelectOption class.
	 *
	 * @param array|NodeElement[] $nodes Nodes.
	 *
	 * @return SelectOption[]
	 */
	protected function wrapOptions(array $nodes)
	{
		$ret = array();

		foreach ( $nodes as $node_element ) {
			$option = SelectOption::fromNodeElement($node_element);
			$ret[] = $option->setSelect($this);
		}

		return $ret;
	}

	/**
	 * Throws an exception when it's not a multiselect.
	 *
	 * @return void
	 * @throws SelectException If the SELECT does not support multiple selections.
	 */
	protected function assertMultiSelect()
	{
		if ( !$this->isMultiple() ) {
			throw new SelectException(
				'You may only deselect all options of a multi-select',
				SelectException::TYPE_NOT_MULTISELECT
			);
		}
	}

}
