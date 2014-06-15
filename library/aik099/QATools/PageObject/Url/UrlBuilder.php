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
 * @method \Mockery\Expectation shouldReceive()
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
	 * The used protocol of the url.
	 *
	 * @var string
	 */
	protected $protocol = '';

	/**
	 * The host of the url.
	 *
	 * @var string
	 */
	protected $host = '';

	/**
	 * GET url parameters.
	 *
	 * @var array
	 */
	protected $params = array();

	/**
	 * Builds united array of params from given $url and $params. Also extracts anchor.
	 *
	 * @param string $url      The given absolute or relative url.
	 * @param array  $params   Additional GET params.
	 * @param string $base_url The base url.
	 *
	 * @throws UrlBuilderException When the path of the given url is empty or a base url is missing.
	 */
	public function __construct($url, array $params = array(), $base_url = null)
	{
		$url_parser = new UrlParser($base_url);
		$url_parser->merge(new UrlParser($url));

		$this->path = $url_parser->getComponent('path');
		$this->host = $url_parser->getComponent('host');
		$this->protocol = $url_parser->getComponent('scheme');
		$this->anchor = $url_parser->getComponent('fragment');

		if ( empty($this->path) ) {
			throw new UrlBuilderException('URL path is missing', UrlBuilderException::TYPE_EMPTY_PATH);
		}

		if ( empty($this->host) ) {
			throw new UrlBuilderException('No base url specified', UrlBuilderException::TYPE_MISSING_BASE_URL);
		}

		$this->params = array_merge($url_parser->getParams(), $params);
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
		$final_url = $this->getProtocol() . '://' . $this->getHost() . $this->getPath();
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

	/**
	 * Get host.
	 *
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * Get used protocol.
	 *
	 * @return string
	 */
	public function getProtocol()
	{
		return $this->protocol;
	}

}
