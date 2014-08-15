<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\Live\HtmlElements\Pages;


use QATools\QATools\HtmlElements\Element\Button;
use QATools\QATools\HtmlElements\Element\Checkbox;
use QATools\QATools\HtmlElements\Element\RadioGroup;
use QATools\QATools\HtmlElements\Element\TextInput;
use QATools\QATools\HtmlElements\TypifiedPage;

class TypifiedElementPage extends TypifiedPage
{

	/**
	 * Example button as Button.
	 *
	 * @var Button
	 * @find-by('id' => 'button1')
	 */
	public $button;

	/**
	 * Example input as TextInput.
	 *
	 * @var TextInput
	 * @find-by('id' => 'text-input')
	 */
	public $input;

	/**
	 * Example checkbox as WebElement.
	 *
	 * @var Checkbox
	 * @find-by('css' => '#checkbox-without-label')
	 */
	public $checkbox;

	/**
	 * Example input for RadioGroup.
	 *
	 * @var RadioGroup
	 * @find-by('name' => 'radio-group1')
	 */
	public $radioGroup;

	/**
	 * Example text inputs as TextInput.
	 *
	 * @var TextInput[]
	 * @find-by('css' => '.test-input')
	 */
	public $textInputs;

}
