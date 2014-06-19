<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject;


use Behat\Mink\Element\NodeElement;

/**
 * All classes, that allow searching elements within them must implement this interface.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
interface ISearchContext
{

	/**
	 * Finds first element with specified selector.
	 *
	 * @param string       $selector Selector engine name.
	 * @param string|array $locator  Selector locator.
	 *
	 * @return NodeElement|null
	 */
	public function find($selector, $locator);

	/**
	 * Finds all elements with specified selector.
	 *
	 * @param string       $selector Selector engine name.
	 * @param string|array $locator  Selector locator.
	 *
	 * @return NodeElement[]
	 */
	public function findAll($selector, $locator);

	/**
	 * Waits for an element(-s) to appear and returns it.
	 *
	 * @param integer  $timeout  Maximal allowed waiting time in milliseconds.
	 * @param callable $callback Callback, which result is both used as waiting condition and returned.
	 *                           Will receive reference to `this element` as first argument.
	 *
	 * @return mixed
	 * @throws \InvalidArgumentException When invalid callback given.
	 */
	public function waitFor($timeout, $callback);

}
