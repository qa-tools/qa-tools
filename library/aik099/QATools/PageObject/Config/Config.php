<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\Config;


use aik099\QATools\PageObject\Exception\ConfigException;

/**
 * Default config class which stores all kind of configurations for the library.
 *
 * @method \Mockery\Expectation shouldReceive()
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
	);

	/**
	 * Creates the config.
	 *
	 * @param array $options Config options.
	 *
	 * @throws ConfigException Throws exception on attempt to set non-existing option.
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
	 * @return $this
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
