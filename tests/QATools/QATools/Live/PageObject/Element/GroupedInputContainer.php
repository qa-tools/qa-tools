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

class GroupedInputContainer extends AbstractElementContainer
{

	/**
	 * Example text inputs with multiple find-by annotations as WebElement.
	 *
	 * @var WebElement[]
	 * @find-by('name' => 'test1')
	 * @find-by('name' => 'test2')
	 * @find-by('name' => 'test3')
	 */
	public $textInputsMultipleFindBy;

}
