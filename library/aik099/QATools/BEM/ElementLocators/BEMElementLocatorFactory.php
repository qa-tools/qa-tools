<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM\ElementLocators;


use aik099\QATools\PageObject\ElementLocators\DefaultElementLocatorFactory;
use aik099\QATools\PageObject\ElementLocators\IElementLocator;
use aik099\QATools\PageObject\Property;

/**
 * Factory to create BEM block/element locators.
 *
 * @method \Mockery\Expectation shouldReceive
 */
class BEMElementLocatorFactory extends DefaultElementLocatorFactory
{

	/**
	 * When a field on a class needs to be decorated with an IElementLocator this method will be called.
	 *
	 * @param Property $property Property.
	 *
	 * @return IElementLocator
	 */
	public function createLocator(Property $property)
	{
		return new BEMElementLocator($property, $this->annotationManager, $this->searchContext);
	}

}
