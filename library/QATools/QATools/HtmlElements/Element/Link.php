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


/**
 * Represents hyperlink.
 */
class Link extends AbstractTypifiedElement
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
	 * @return static
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
