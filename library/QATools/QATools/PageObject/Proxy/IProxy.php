<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\Proxy;


use QATools\QATools\PageObject\Exception\ElementException;
use QATools\QATools\PageObject\Exception\ElementNotFoundException;

/**
 * All page factories must implement this interface.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
interface IProxy
{

	/**
	 * Sets class name, used inside the proxy.
	 *
	 * @param string $class_name Class name to proxy.
	 *
	 * @return self
	 */
	public function setClassName($class_name);

	/**
	 * Returns class instance, that was placed inside a proxy.
	 *
	 * @return mixed
	 * @throws ElementNotFoundException When element wasn't found on the page.
	 */
	public function getObject();

	/**
	 * Proxies read access for properties to the sub-object.
	 *
	 * @param string $property Property to proxy.
	 *
	 * @return mixed
	 */
	public function __get($property);

	/**
	 * Proxies write access for properties to the sub-object.
	 *
	 * @param string $property Property to proxy.
	 * @param mixed  $value    Property value.
	 *
	 * @return void
	 */
	public function __set($property, $value);

	/**
	 * Proxies all methods to sub-object.
	 *
	 * @param string $method    Method to proxy.
	 * @param array  $arguments Method arguments.
	 *
	 * @return mixed
	 * @throws ElementException When sub-object doesn't have a specified method.
	 */
	public function __call($method, array $arguments);

}
