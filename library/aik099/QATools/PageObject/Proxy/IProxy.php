<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\Proxy;


use aik099\QATools\PageObject\Element\IContainerAware;
use aik099\QATools\PageObject\Exception\ElementException;
use aik099\QATools\PageObject\Exception\ElementNotFoundException;

/**
 * All page factories must implement this interface.
 *
 * @method \Mockery\Expectation shouldReceive()
 */
interface IProxy extends IContainerAware
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
