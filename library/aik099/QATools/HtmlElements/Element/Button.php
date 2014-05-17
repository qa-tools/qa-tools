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


/**
 * Represents web page button control.
 */
class Button extends AbstractTypifiedElement
{

	/**
	 * Clicks the button.
	 *
	 * @return self
	 */
	public function click()
	{
		$this->getWrappedElement()->click();

		return $this;
	}

}
