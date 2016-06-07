<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\HtmlElements\Fixture\Page;


use QATools\QATools\HtmlElements\Element\TextInput;
use QATools\QATools\HtmlElements\TypifiedPage;
use QATools\QATools\PageObject\Page;

class TypifiedPageChild extends TypifiedPage
{

	/**
	 * Web Element, that relies on "use ..." clause in the class.
	 *
	 * @var TextInput
	 * @find-by('xpath' => 'xpath1')
	 */
	public $elementWithUse;

	/**
	 * Element, that has absolute class name.
	 *
	 * @var \QATools\QATools\PageObject\Element\WebElement
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
