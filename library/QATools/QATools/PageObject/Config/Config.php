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
	 */
	public function setOption($name, $value)
	{
		$this->assertOptionName($name);

		$this->options[$name] = $value;

		return $this;
	}

	/**
	 * Get configuration option value.
	 *
	 * @param string $name Config option name.
	 *
	 * @return mixed
	 */
	public function getOption($name)
	{
		$this->assertOptionName($name);

		return $this->options[$name];
	}

	/**
	 * Checks, that option exists in config.
	 *
	 * @param string $name Option name.
	 *
	 * @return void
	 * @throws ConfigException Thrown when option with a given name doesn't exist.
	 */
	protected function assertOptionName($name)
	{
		if ( !isset($this->options[$name]) ) {
			throw new ConfigException(
				'Option "' . $name . '" doesn\'t exist in configuration',
				ConfigException::TYPE_NOT_FOUND
			);
		}
	}

}
