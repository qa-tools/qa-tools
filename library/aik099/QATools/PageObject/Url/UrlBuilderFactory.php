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
 * @method \Mockery\Expectation shouldReceive()
 */
class UrlBuilderFactory implements IUrlBuilderFactory
{

	/**
	 * Returns an instance of a class implementing IUrlBuilder interface based on given arguments.
	 *
	 * @param string $path     The url/path.
	 * @param array  $params   The additional GET params.
	 * @param string $base_url The base url of the url builder.
	 *
	 * @return IUrlBuilder
	 */
	public function getUrlBuilder($path, array $params = array(), $base_url = '')
	{
		return new UrlBuilder($path, $params, $base_url);
	}

}
