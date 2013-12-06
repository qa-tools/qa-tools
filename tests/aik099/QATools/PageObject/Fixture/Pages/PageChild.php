<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\PageObject\Fixture\Pages;


use aik099\QATools\PageObject\Elements\WebElement;
use aik099\QATools\PageObject\Page;

class PageChild extends Page
{

	/**
	 * Web Element, that relies on "use ..." clause in the class.
	 *
	 * @var WebElement
	 * @find-by('xpath' => 'xpath1')
	 */
	public $elementWithUse;

	/**
	 * Element, that has absolute class name.
	 *
	 * @var \aik099\QATools\PageObject\Elements\WebElement
	 * @find-by('xpath' => 'xpath2')
	 */
	public $elementWithoutUse;

	/**
	 * Class, that is not supported.
	 *
	 * @var Page
	 */
	public $notSupportedClass;
}
