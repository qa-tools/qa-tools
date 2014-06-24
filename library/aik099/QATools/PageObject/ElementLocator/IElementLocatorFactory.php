<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace aik099\QATools\PageObject\ElementLocator;


use aik099\QATools\PageObject\Property;

/**
 * A factory for producing IElementLocators. It is expected that a new IElementLocator will be returned per call.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
interface IElementLocatorFactory
{

	/**
	 * When a field on a class needs to be decorated with an IElementLocator this method will be called.
	 *
	 * @param Property $property Property.
	 *
	 * @return IElementLocator
	 */
	public function createLocator(Property $property);

}
