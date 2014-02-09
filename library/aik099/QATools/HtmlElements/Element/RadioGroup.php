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


use Behat\Mink\Element\NodeElement;
use aik099\QATools\HtmlElements\Exception\TypifiedElementException;

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
				"self::*",
				"following::input[@type = 'radio']",
				"preceding::input[@type = 'radio']",
			);
		}
		else {
			$radio_name = $this->getSelectorsHandler()->xpathLiteral($radio_name);

			$xpath_expressions = array(
				"self::*",
				"following::input[@type = 'radio' and @name = " . $radio_name . "]",
				"preceding::input[@type = 'radio' and @name = " . $radio_name . "]",
			);
		}

		$ret = array();

		foreach ( $xpath_expressions as $xpath_expression ) {
			$ret = array_merge($ret, $this->findAll('xpath', $xpath_expression));
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
	 * @return NodeElement Element, that represents selected radio button or {@code null} if no radio buttons are selected.
	 * @throws TypifiedElementException When no radio button is selected.
	 */
	public function getSelectedButton()
	{
		foreach ( $this->getButtons() as $button ) {
			if ( $button->isSelected() ) {
				return $button;
			}
		}

		throw new TypifiedElementException('No selected button');
	}

	/**
	 * Selects radio button, that contains given text.
	 *
	 * @param string $text Text.
	 *
	 * @return self
	 * @throws TypifiedElementException When radio button with given label text wasn't found.
	 */
	public function selectButtonByLabelText($text)
	{
		foreach ( $this->getButtons() as $button ) {
			if ( strpos($button->getLabelText(), $text) !== false ) {
				$button->select();

				return $this;
			}
		}

		throw new TypifiedElementException('Cannot locate radio button with label text containing: ' . $text);
	}

	/**
	 * Selects radio button that have a value matching the specified argument.
	 *
	 * @param string $value The value to match against.
	 *
	 * @return self
	 * @throws TypifiedElementException When radio button with given value wasn't found.
	 */
	public function selectButtonByValue($value)
	{
		foreach ( $this->getButtons() as $button ) {
			$button_value = $button->getAttribute('value');

			if ( "$button_value" === "$value" ) {
				$button->select();

				return $this;
			}
		}

		throw new TypifiedElementException('Cannot locate radio button with value: ' . $value);
	}

	/**
	 * Selects radio button by the given index.
	 *
	 * @param integer $index Index of a radio button to be selected.
	 *
	 * @return self
	 * @throws TypifiedElementException When non-existing index was given.
	 */
	public function selectButtonByIndex($index)
	{
		$buttons = $this->getButtons();

		if ( array_key_exists($index, $buttons) ) {
			$buttons[$index]->select();

			return $this;
		}

		throw new TypifiedElementException('Cannot locate radio button with index: ' . $index);
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
