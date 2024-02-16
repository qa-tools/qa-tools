<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject;


use Behat\Mink\Element\NodeElement;
use QATools\QATools\PageObject\Exception\ElementException;

trait TWrappedElement
{

	/**
	 * Finds first element with specified selector.
	 *
	 * @param string       $selector Selector engine name.
	 * @param string|array $locator  Selector locator.
	 *
	 * @return NodeElement|null
	 */
	public function find($selector, $locator)
	{
		return $this->_wrappedElement->find($selector, $locator);
	}

	/**
	 * Finds all elements with specified selector.
	 *
	 * @param string       $selector Selector engine name.
	 * @param string|array $locator  Selector locator.
	 *
	 * @return NodeElement[]
	 */
	public function findAll($selector, $locator)
	{
		return $this->_wrappedElement->findAll($selector, $locator);
	}

	/**
	 * Waits for an element(-s) to appear and returns it.
	 *
	 * @param integer  $timeout  Maximal allowed waiting time in seconds.
	 * @param callable $callback Callback, which result is both used as waiting condition and returned.
	 *                           Will receive reference to `this element` as first argument.
	 *
	 * @return mixed
	 */
	public function waitFor($timeout, $callback)
	{
		$container = $this;
		$wrapped_callback = function () use ($container, $callback) {
			return call_user_func($callback, $container);
		};

		return $this->_wrappedElement->waitFor($timeout, $wrapped_callback);
	}

	/**
	 * Proxies all methods to sub-object.
	 *
	 * @param string $method    Method to proxy.
	 * @param array  $arguments Method arguments.
	 *
	 * @return mixed
	 * @throws ElementException When sub-object doesn't have a specified method.
	 */
	public function __call($method, array $arguments)
	{
		if ( !method_exists($this->_wrappedElement, $method) && !method_exists($this->_wrappedElement, '__call') ) {
			$message = sprintf('"%s" method is not available on the %s', $method, get_class($this->_wrappedElement));

			throw new ElementException($message, ElementException::TYPE_UNKNOWN_METHOD);
		}

		return call_user_func_array(array($this->_wrappedElement, $method), $arguments);
	}

}
