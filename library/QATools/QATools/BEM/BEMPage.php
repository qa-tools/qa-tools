<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\BEM;


use QATools\QATools\PageObject\Page;

abstract class BEMPage extends Page
{

	/**
	 * Initializes BEM page elements.
	 *
	 * @return void
	 */
	public function initElements()
	{
		$this->pageFactory->initElements($this, $this->pageFactory->createDecorator($this));
	}

}
