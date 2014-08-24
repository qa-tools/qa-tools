<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\PageLocator;


/**
 * Interface to get fully qualified class names by given names of a page.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
interface IPageLocator
{

	/**
	 * Returns the fully qualified class name of a page by its name.
	 *
	 * @param string $name The name of the page.
	 *
	 * @return string
	 */
	public function resolvePage($name);

}
