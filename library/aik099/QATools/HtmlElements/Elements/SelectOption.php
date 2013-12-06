<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\HtmlElements\Elements;


use Behat\Mink\Element\NodeElement;
use aik099\QATools\HtmlElements\Exceptions\TypifiedElementException;
use aik099\QATools\PageObject\Elements\IWebElement;
use aik099\QATools\PageObject\Elements\WebElement;


/**
 * Represents one option in a web page select control.
 */
class SelectOption extends TypifiedElement
{

	/**
	 * SELECT element.
	 *
	 * @var Select
	 */
	protected $select;

	/**
	 * Specifies wrapped WebElement.
	 *
	 * @param IWebElement $wrapped_element Element to be wrapped.
	 * @param Select      $select          Associated SELECT element.
	 */
	public function __construct(IWebElement $wrapped_element, Select $select)
	{
		$this->select = $select;

		parent::__construct($wrapped_element);
	}

	/**
	 * Selects option if it is not already selected.
	 *
	 * @param boolean $multiple Append this option to current selection.
	 *
	 * @return self
	 */
	public function select($multiple = false)
	{
		if ( !$this->isSelected() ) {
			$this->select->getWrappedElement()->selectOption($this->getValue(), $multiple);
		}

		return $this;
	}

	/**
	 * Deselects option if it is not already deselected.
	 *
	 * @return self
	 * @throws TypifiedElementException When non-Selenium driver is used.
	 */
	public function deselect()
	{
		if ( $this->isSelected() ) {
			if ( !$this->isSeleniumDriver() ) {
				throw new TypifiedElementException('Deselecting individual options is only supported in Selenium drivers');
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

	/**
	 * Creates WebElement instance based on existing NodeElement instance.
	 *
	 * @param NodeElement $node_element Node element.
	 * @param Select      $select       Select.
	 *
	 * @return TypifiedElement
	 */
	public static function fromNodeElement(NodeElement $node_element, Select $select)
	{
		$wrapped_element = WebElement::fromNodeElement($node_element);

		return new static($wrapped_element, $select);
	}

}
