<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Fixture\Element;


use QATools\QATools\PageObject\Element\WebElementCollection;

class WebElementCollectionChild extends WebElementCollection
{

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
