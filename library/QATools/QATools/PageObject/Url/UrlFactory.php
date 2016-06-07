<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\Url;


/**
 * Responsible for building the URL of pages.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class UrlFactory implements IUrlFactory
{

	/**
	 * Returns an instance of a class implementing IBuilder interface based on given arguments.
	 *
	 * @param array $components The url components.
	 *
	 * @return IBuilder
	 */
	public function getBuilder(array $components)
	{
		return new Builder($components);
	}

}
