<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\ElementLocator;


use Behat\Mink\Element\NodeElement;
use aik099\QATools\PageObject\ISearchContext;


/**
 * Classes, that can search elements on a page must implement this interface.
 *
 * @method \Mockery\Expectation shouldReceive
 */
interface IElementLocator
{

	/**
	 * Returns search context in use.
	 *
	 * @return ISearchContext
	 */
	public function getSearchContext();

	/**
	 * Find the element.
	 *
	 * @return NodeElement|null
	 */
	public function find();

	/**
	 * Find the element list.
	 *
	 * @return NodeElement[]
	 */
	public function findAll();

	/**
	 * Returns string representation of a locator.
	 *
	 * @return string
	 */
	public function __toString();

}
