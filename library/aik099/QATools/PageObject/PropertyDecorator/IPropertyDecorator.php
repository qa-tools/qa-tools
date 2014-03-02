<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\PropertyDecorator;


use aik099\QATools\PageObject\Proxy\IProxy;
use aik099\QATools\PageObject\Property;

/**
 * Allows the PageFactory to decorate fields.
 *
 * @method \Mockery\Expectation shouldReceive
 */
interface IPropertyDecorator
{

	/**
	 * This method is called by PageFactory on all properties to decide how to decorate the property.
	 *
	 * @param Property $property The property that may be decorated.
	 *
	 * @return IProxy
	 */
	public function decorate(Property $property);

}
