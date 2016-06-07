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


/**
 * Interface for all kind of configurations for the library.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
interface IConfig
{

	/**
	 * Change configuration option value.
	 *
	 * @param string $name  Config option name.
	 * @param mixed  $value Config option value.
	 *
	 * @return self
	 */
	public function setOption($name, $value);

	/**
	 * Get configuration option value.
	 *
	 * @param string $name Config option name.
	 *
	 * @return mixed
	 */
	public function getOption($name);

}
