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


use Mockery\Expectation;

/**
 * Parses url and returns components.
 *
 * @method Expectation shouldReceive
 */
class UrlParser
{

	/**
	 * The url components.
	 *
	 * @var array
	 */
	protected $components = array();

	/**
	 * Constructor for UrlParser.
	 *
	 * @param string $url The url to parse.
	 */
	public function __construct($url)
	{
		$this->components = parse_url($url);
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
	 * Get parsed query.
	 *
	 * @return array
	 */
	public function getParams()
	{
		$result = array();
		$query = $this->getComponent('query');

		if ( $query ) {
			parse_str($query, $result);
		}

		return $result;
	}

	/**
	 * Merge both url parsers.
	 *
	 * @param UrlParser $url_parser The url parser to merge.
	 *
	 * @return $this
	 */
	public function merge(UrlParser $url_parser)
	{
		$this->components = array_merge($this->components, $url_parser->components);

		return $this;
	}

}
