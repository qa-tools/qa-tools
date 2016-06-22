<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\Live\PageObject\Element;


use QATools\QATools\PageObject\Element\AbstractElementContainer;
use QATools\QATools\PageObject\Element\WebElement;

/**
 * @find-by('css' => '.grouped-inputs')
 */
class InputContainer extends AbstractElementContainer
{

	/**
	 * First input in container
	 *
	 * @var WebElement
	 * @find-by('css' => 'input[name=test1]')
	 */
	public $textInput;

}
