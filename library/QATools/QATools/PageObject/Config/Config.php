<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\Config;


use QATools\QATools\PageObject\Exception\ConfigException;

/**
 * Default config class which stores all kind of configurations for the library.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class Config implements IConfig
{

	/**
	 * Container for the options.
	 *
	 * @var array
	 */
	protected $options = array(
		'base_url' => '',
		'page_namespace_prefix' => array('\\'),
		'page_url_matchers' => array(
			'\\QATools\\QATools\\PageObject\\PageUrlMatcher\\ExactPageUrlMatcher',
			'\\QATools\\QATools\\PageObject\\PageUrlMatcher\\RegexpPageUrlMatcher',
			'\\QATools\\QATools\\PageObject\\PageUrlMatcher\\ComponentPageUrlMatcher',
		),
	);

	/**
	 * Creates the config.
	 *
	 * @param array $options Config options.
	 */
	public function __construct(array $options = array())
	{
		foreach ( $options as $name => $value ) {
			$this->setOption($name, $value);
		}
	}

	/**
	 * Change configuration option value.
	 *
	 * @param string $name  Config option name.
	 * @param mixed  $value Config option value.
	 *
	 * @return self
	 * @throws ConfigException Throws exception on attempt to set non-existing option.
	 */
	public function setOption($name, $value)
	{
		if ( !isset($this->options[$name]) ) {
			throw new ConfigException(
				'Option "' . $name . '" doesn\'t exist in configuration',
				ConfigException::TYPE_NOT_FOUND
			);
		}

		$this->options[$name] = $value;

		return $this;
	}

	/**
	 * Get configuration option value.
	 *
	 * @param string $name Config option name.
	 *
	 * @return mixed
	 * @throws ConfigException Thrown when option with a given name doesn't exist.
	 */
	public function getOption($name)
	{
		if ( !isset($this->options[$name]) ) {
			throw new ConfigException(
				'Option "' . $name . '" doesn\'t exist in configuration',
				ConfigException::TYPE_NOT_FOUND
			);
		}

		return $this->options[$name];
	}

}
