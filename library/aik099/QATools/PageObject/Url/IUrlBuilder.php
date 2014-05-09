<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\Url;


/**
 * Responsible for building the URL of pages.
 *
 * @method \Mockery\Expectation shouldReceive
 */
interface IUrlBuilder
{

	/**
	 * Builds url using given GET parameters.
	 *
	 * @param array $params Additional GET params.
	 *
	 * @return string
	 */
	public function build(array $params = array());

}
