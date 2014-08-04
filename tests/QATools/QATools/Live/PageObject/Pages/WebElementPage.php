<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\Live\PageObject\Pages;


use QATools\QATools\PageObject\Element\WebElement;
use QATools\QATools\PageObject\Page;

class WebElementPage extends Page
{

	/**
	 * Example button as WebElement.
	 *
	 * @var WebElement
	 * @find-by('id' => 'button1')
	 */
	public $button;

	/**
	 * Example checkbox as WebElement.
	 *
	 * @var WebElement
	 * @find-by('css' => '#checkbox-without-label')
	 */
	public $checkbox;

	/**
	 * Example radio buttons as WebElement.
	 *
	 * @var WebElement
	 * @find-by('name' => 'radio-group1')
	 */
	public $radioGroup;
}
