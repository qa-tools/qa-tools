<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\HtmlElements\Fixture\Element;


use aik099\QATools\HtmlElements\Element\AbstractTypifiedElementCollection;

class TypifiedElementCollectionChild extends AbstractTypifiedElementCollection
{

	/**
	 * Initializes collection with a list of elements.
	 *
	 * @param array $elements Elements.
	 */
	public function __construct(array $elements = array())
	{
		// Without this attempt to call "fromNodeElements" method from proxy test will fail.
		$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\TextInput';

		parent::__construct($elements);
	}

	/**
	 * Method for testing proxy abilities.
	 *
	 * @return integer
	 */
	public function proxyMe()
	{
		return 1;
	}

}
