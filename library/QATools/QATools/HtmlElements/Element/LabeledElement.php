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


use Behat\Mink\Element\NodeElement;

/**
 * Represents web page control with optional associated label.
 */
class LabeledElement extends AbstractTypifiedElement
{

	/**
	 * Finds label corresponding to current element.
	 *
	 * @return NodeElement|null Element representing label or null if no label has been found.
	 */
	public function getLabel()
	{
		$label = null;
		$id = $this->getAttribute('id');

		if ( $id !== null ) {
			// Label with matching "for" attribute.
			$escaped_id = $this->getXpathEscaper()->escapeLiteral($id);
			$xpath_expressions = array(
				'preceding::label[@for = ' . $escaped_id . ']',
				'following::label[@for = ' . $escaped_id . ']',
			);
			$label = $this->getWrappedElement()->find('xpath', '(' . implode(' | ', $xpath_expressions) . ')[1]');
		}

		if ( $label === null ) {
			// Label wrapped around checkbox.
			$label = $this->getWrappedElement()->find('xpath', 'parent::label');
		}

		if ( $label === null ) {
			// Label right next to checkbox.
			$label = $this->getWrappedElement()->find('xpath', 'following-sibling::*[1][self::label]');
		}

		return $label;
	}

	/**
	 * Finds a text of the current element label.
	 *
	 * @return null|string Label text or null if no label has been found.
	 */
	public function getLabelText()
	{
		$label = $this->getLabel();

		return $label === null ? null : $label->getText();
	}

	/**
	 * Finds a text of the current element label.
	 *
	 * @return null|string Text of the associated label or null if no label has been found.
	 * @see    LabeledElement::getLabelText()
	 */
	public function getText()
	{
		return $this->getLabelText();
	}

}
