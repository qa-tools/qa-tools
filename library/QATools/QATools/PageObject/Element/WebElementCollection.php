<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\Element;


use QATools\QATools\PageObject\ISearchContext;

/**
 * Represents a list of elements.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class WebElementCollection extends AbstractElementCollection implements IWebElement
{

	/**
	 * Initializes collection with a list of elements.
	 *
	 * @param array $elements Elements.
	 */
	public function __construct(array $elements = array())
	{
		if ( !$this->elementClass ) {
			$this->elementClass = '\\QATools\\QATools\\PageObject\\Element\\WebElement';
		}

		parent::__construct($elements);
	}

}
