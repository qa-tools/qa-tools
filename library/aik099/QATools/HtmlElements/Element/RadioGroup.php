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


use aik099\QATools\HtmlElements\Exception\RadioGroupException;
use Behat\Mink\Element\NodeElement;

/**
 * Represents a group of radio buttons.
 */
class RadioGroup extends TypifiedElement implements ISimpleSetter
{

	/**
	 * Returns all radio buttons belonging to this group.
	 *
	 * @return Radio[]
	 */
	public function getButtons()
	{
		$radio_name = $this->getAttribute('name');

		if ( is_null($radio_name) ) {
			$xpath_expressions = array(
				'self::*',
				"following::input[@type = 'radio']",
				"preceding::input[@type = 'radio']",
			);
		}
		else {
			$radio_name = $this->getSelectorsHandler()->xpathLiteral($radio_name);

			$xpath_expressions = array(
				'self::*',
				"following::input[@type = 'radio' and @name = " . $radio_name . ']',
				"preceding::input[@type = 'radio' and @name = " . $radio_name . ']',
			);
		}

		$ret = array();

		foreach ( $xpath_expressions as $xpath_expression ) {
			$ret = array_merge($ret, $this->getWrappedElement()->findAll('xpath', $xpath_expression));
		}

		return $this->wrapButtons($ret);
	}

	/**
	 * Wraps each of NodeElement in array with a Radio class.
	 *
	 * @param array|NodeElement[] $nodes Nodes.
	 *
	 * @return Radio[]
	 */
	protected function wrapButtons(array $nodes)
	{
		$ret = array();

		foreach ( $nodes as $node_element ) {
			$ret[] = Radio::fromNodeElement($node_element);
		}

		return $ret;
	}

	/**
	 * Indicates if radio has selected button.
	 *
	 * @return boolean
	 */
	public function hasSelectedButton()
	{
		foreach ( $this->getButtons() as $button ) {
			if ( $button->isSelected() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns selected radio button.
	 *
	 * @return Radio Element, that represents selected radio button or {@code null} if no radio buttons are selected.
	 * @throws RadioGroupException When no radio button is selected.
	 */
	public function getSelectedButton()
	{
		foreach ( $this->getButtons() as $button ) {
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
		foreach ( $this->getButtons() as $button ) {
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
		foreach ( $this->getButtons() as $button ) {
			$button_value = $button->getAttribute('value');

			if ( (string)$button_value === (string)$value ) {
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
		$buttons = $this->getButtons();

		if ( array_key_exists($index, $buttons) ) {
			$buttons[$index]->select();

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
