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

		if ( !is_null($id) ) {
			// Label with matching "for" attribute.
			$escaped_id = $this->getSelectorsHandler()->xpathLiteral($id);
			$label = $this->getContainer()->find('xpath', 'descendant-or-self::label[@for = ' . $escaped_id . ']');
		}

		if ( is_null($label) ) {
			// Label wrapped around checkbox.
			$label = $this->getWrappedElement()->find('xpath', 'parent::label');
		}

		if ( is_null($label) ) {
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

		return is_null($label) ? null : $label->getText();
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
