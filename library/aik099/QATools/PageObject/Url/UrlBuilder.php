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


use aik099\QATools\PageObject\Exception\UrlBuilderException;

/**
 * Responsible for building the URL of pages.
 *
 * @method \Mockery\Expectation shouldReceive
 */
class UrlBuilder implements IUrlBuilder
{

	/**
	 * Path component of the url (e.g. /path/to/page.html).
	 *
	 * @var string
	 */
	protected $path = '';

	/**
	 * The anchor component of the url (e.g. "shoes", if the url is "/view/category#shoes").
	 *
	 * @var string
	 */
	protected $anchor = '';

	/**
	 * GET url parameters.
	 *
	 * @var array
	 */
	protected $params = array();

	/**
	 * Builds united array of params from given $path and $params. Also extracts anchor.
	 *
	 * @param string $path   The given url path.
	 * @param array  $params Additional GET params.
	 *
	 * @throws UrlBuilderException When the path of the given url is empty.
	 */
	public function __construct($path, array $params = array())
	{
		$url_components = parse_url($path);

		$this->path = $url_components['path'];

		if ( empty($this->path) ) {
			throw new UrlBuilderException('URL path is missing', UrlBuilderException::TYPE_EMPTY_PATH);
		}

		$this->anchor = isset($url_components['fragment']) ? $url_components['fragment'] : '';
		$this->params = $params;

		if ( isset($url_components['query']) ) {
			$parsed_params = array();
			parse_str($url_components['query'], $parsed_params);
			$this->params = array_merge($parsed_params, $this->params);
		}
	}

	/**
	 * Builds the final url and merges saved params with given via parameter.
	 *
	 * @param array $params The additional GET params.
	 *
	 * @return string
	 */
	public function build(array $params = array())
	{
		$final_url = $this->getPath();
		$final_params = array_merge($this->getParams(), $params);

		if ( !empty($final_params) ) {
			$final_url .= '?' . http_build_query($final_params);
		}

		if ( $this->getAnchor() != '' ) {
			$final_url .= '#' . $this->getAnchor();
		}

		return $final_url;
	}

	/**
	 * Get params.
	 *
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * Get path.
	 *
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Get anchor.
	 *
	 * @return string
	 */
	public function getAnchor()
	{
		return $this->anchor;
	}

}
