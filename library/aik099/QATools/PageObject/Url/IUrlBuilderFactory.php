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
 * All url builder factories must implement this interface.
 *
 * @method \Mockery\Expectation shouldReceive
 */
interface IUrlBuilderFactory
{

	/**
	 * Returns an instance of a class implementing IUrlBuilder interface based on given arguments.
	 *
	 * @param string $path     The path of the URL after the domain.
	 * @param array  $params   Additional GET params as an array.
	 * @param string $base_url The base url of the url builder.
	 *
	 * @return IUrlBuilder
	 */
	public function getUrlBuilder($path, array $params = array(), $base_url = '');

}
