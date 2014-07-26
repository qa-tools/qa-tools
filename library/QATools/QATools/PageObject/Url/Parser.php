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

/**
 * Parses url and returns components.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class Parser
{

	/**
	 * The url components.
	 *
	 * @var array
	 */
	protected $components = array();

	/**
	 * Constructor for Parser.
	 *
	 * @param string $url The url to parse.
	 *
	 * @throws UrlException When url is invalid.
	 */
	public function __construct($url)
	{
		$components = parse_url($url);

		if ( $components === false ) {
			throw new UrlException($url . ' is not a valid url', UrlException::TYPE_INVALID_URL);
		}

		$this->components = $components;
	}

	/**
	 * Get url component.
	 *
	 * @param string $name    The name of the component.
	 * @param string $default The default value.
	 *
	 * @return string
	 */
	public function getComponent($name, $default = '')
	{
		return !empty($this->components[$name]) ? $this->components[$name] : $default;
	}

	/**
	 * Gets the url components.
	 *
	 * @return array
	 */
	public function getComponents()
	{
		return $this->components;
	}

	/**
	 * Gets parsed query.
	 *
	 * @return array
	 */
	public function getParams()
	{
		$ret = array();
		$query = $this->getComponent('query');

		if ( $query ) {
			parse_str($query, $ret);
		}

		return $ret;
	}

	/**
	 * Sets parsed query.
	 *
	 * @param array $params GET params.
	 *
	 * @return self
	 */
	public function setParams(array $params)
	{
		$this->components['query'] = http_build_query($params);

		return $this;
	}

	/**
	 * Merge both url parsers.
	 *
	 * @param Parser $parser The url parser to merge.
	 *
	 * @return self
	 */
	public function merge(Parser $parser)
	{
		$this->components = array_merge($this->components, $parser->components);

		return $this;
	}

}
