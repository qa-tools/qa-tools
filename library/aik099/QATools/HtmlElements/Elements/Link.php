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


/**
 * Represents hyperlink.
 */
class Link extends TypifiedElement
{

	/**
	 * Retrieves url from "href" tag.
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return $this->getAttribute('href');
	}

	/**
	 * Click the link.
	 *
	 * @return self
	 */
	public function click()
	{
		$this->getWrappedElement()->click();

		return $this;
	}

	/**
	 * Returns text of the link.
	 *
	 * @return string
	 */
	public function getText()
	{
		return $this->getWrappedElement()->getText();
	}

}
