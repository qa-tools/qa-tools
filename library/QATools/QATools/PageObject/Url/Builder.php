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


use QATools\QATools\PageObject\Exception\UrlException;
use QATools\QATools\PageObject\Exception\MissingParametersException;

/**
 * Responsible for building the URL of pages.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class Builder implements IBuilder
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
	 * The port of the url.
	 *
	 * @var integer|null
	 */
	protected $port = null;

	/**
	 * GET url parameters.
	 *
	 * @var array
	 */
	protected $params = array();

	/**
	 * Constructor for the url builder.
	 *
	 * @param array $components The url components.
	 *
	 * @throws UrlException When the path, host or protocol are missing.
	 */
	public function __construct(array $components)
	{
		if ( empty($components['scheme']) || empty($components['host']) || empty($components['path']) ) {
			throw new UrlException('No base url specified', UrlException::TYPE_INVALID_URL);
		}

		$this->path = $components['path'];
		$this->host = $components['host'];
		$this->protocol = $components['scheme'];

		$this->port = !empty($components['port']) ? $components['port'] : '';
		$this->anchor = !empty($components['fragment']) ? $components['fragment'] : '';

		if ( !empty($components['query']) ) {
			parse_str($components['query'], $this->params);
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
		$final_url = $this->getProtocol() . '://' . $this->getHost() . $this->getPortForBuild() . $this->getPath();
		$final_params = array_merge($this->getParams(), $params);

		list($final_url, $final_params) = $this->unmaskUrl($final_url, $final_params);

		if ( !empty($final_params) ) {
			$final_url .= '?' . http_build_query($final_params);
		}

		if ( $this->getAnchor() != '' ) {
			$final_url .= '#' . $this->getAnchor();
		}

		return $final_url;
	}

	/**
	 * Get the final port for build.
	 *
	 * @return string
	 */
	protected function getPortForBuild()
	{
		if ( !$this->getPort() ) {
			return '';
		}

		$default_ports = array('http' => 80, 'https' => 443);

		if ( $this->getPort() === $default_ports[$this->getProtocol()] ) {
			return '';
		}

		return ':' . $this->getPort();
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

	/**
	 * Get used port.
	 *
	 * @return integer
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * Unmasks a URL and replaces masks with a parameter value.
	 *
	 * @param string $url    URL to be unmasked.
	 * @param array  $params Params holding values for masks.
	 *
	 * @return array Returns an array of 0 => unmasked url and 1 => parameters.
	 * @throws MissingParametersException Thrown if a mask was found in $url which does not exist in $params.
	 */
	protected function unmaskUrl($url, array $params)
	{
		if ( !preg_match_all('/\{([^{}]+)\}/', $url, $matches) ) {
			return array($url, $params);
		}

		$url_masks = array_unique($matches[0]);
		$parameter_names = array_unique($matches[1]);
		$mask_replacements = array();
		$missing_parameters = array();

		foreach ( $parameter_names as $parameter_name ) {
			if ( !array_key_exists($parameter_name, $params) ) {
				$missing_parameters[] = $parameter_name;
				continue;
			}

			$mask_replacements[] = rawurlencode($params[$parameter_name]);
			unset($params[$parameter_name]);
		}

		if ( $missing_parameters ) {
			throw new MissingParametersException($missing_parameters);
		}

		return array(str_replace($url_masks, $mask_replacements, $url), $params);
	}

}
