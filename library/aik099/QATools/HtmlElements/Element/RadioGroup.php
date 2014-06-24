<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace aik099\QATools\HtmlElements\Element;


use aik099\QATools\HtmlElements\Exception\RadioGroupException;

/**
 * Represents a group of radio buttons.
 */
class RadioGroup extends AbstractTypifiedElementCollection implements ISimpleSetter
{

	/**
	 * Initializes collection with a list of elements.
	 *
	 * @param array|RadioButton[] $elements RadioButton elements.
	 */
	public function __construct(array $elements = array())
	{
		if ( !$this->elementClass ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\RadioButton';
		}

		parent::__construct($elements);
	}

	/**
	 * Determines if an element can be added to a collection.
	 *
	 * @param mixed $element Element.
	 *
	 * @return boolean
	 */
	protected function acceptElement($element)
	{
		return $element->getTagName() == 'input' && strtolower($element->getAttribute('type')) == 'radio';
	}

	/**
	 * Indicates if radio has selected button.
	 *
	 * @return boolean
	 */
	public function hasSelectedButton()
	{
		/** @var $button RadioButton */
		foreach ( $this as $button ) {
			if ( $button->isSelected() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns selected radio button.
	 *
	 * @return RadioButton Element, that represents selected radio button or {@code null} if no radio buttons are selected.
	 * @throws RadioGroupException When no radio button is selected.
	 */
	public function getSelectedButton()
	{
		/** @var $button RadioButton */
		foreach ( $this as $button ) {
			if ( $button->isSelected() ) {
				return $button;
			}
		}

		throw new RadioGroupException('No selected button', RadioGroupException::TYPE_NOT_SELECTED);
	}

	/**
	 * Selects radio button, that contains given text.
	 *
	 * @param string $text Text.
	 *
	 * @return self
	 * @throws RadioGroupException When radio button with given label text wasn't found.
	 */
	public function selectButtonByLabelText($text)
	{
		/** @var $button RadioButton */
		foreach ( $this as $button ) {
			if ( strpos($button->getLabelText(), $text) !== false ) {
				$button->select();

				return $this;
			}
		}

		throw new RadioGroupException(
			'Cannot locate radio button with label text containing: ' . $text,
			RadioGroupException::TYPE_NOT_FOUND
		);
	}

	/**
	 * Selects radio button that have a value matching the specified argument.
	 *
	 * @param string $value The value to match against.
	 *
	 * @return self
	 * @throws RadioGroupException When radio button with given value wasn't found.
	 */
	public function selectButtonByValue($value)
	{
		/** @var $button RadioButton */
		foreach ( $this as $button ) {
			if ( (string)$button->getValue() === (string)$value ) {
				$button->select();

				return $this;
			}
		}

		throw new RadioGroupException(
			'Cannot locate radio button with value: ' . $value,
			RadioGroupException::TYPE_NOT_FOUND
		);
	}

	/**
	 * Selects radio button by the given index.
	 *
	 * @param integer $index Index of a radio button to be selected.
	 *
	 * @return self
	 * @throws RadioGroupException When non-existing index was given.
	 */
	public function selectButtonByIndex($index)
	{
		if ( isset($this[$index]) ) {
			/** @var RadioButton $button */
			$button = $this[$index];
			$button->select();

			return $this;
		}

		throw new RadioGroupException(
			'Cannot locate radio button with index: ' . $index,
			RadioGroupException::TYPE_NOT_FOUND
		);
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
		return $this->selectButtonByValue((string)$value);
	}

}
