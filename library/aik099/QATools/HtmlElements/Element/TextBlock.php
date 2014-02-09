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
 * Represents text block on a web page.
 */
class TextBlock extends TypifiedElement
{

	/**
	 * Returns text contained in text block.
	 *
	 * @return string
	 */
	public function getText()
	{
		return $this->getWrappedElement()->getText();
	}

}
