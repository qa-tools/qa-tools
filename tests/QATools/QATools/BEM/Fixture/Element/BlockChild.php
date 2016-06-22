<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\BEM\Fixture\Element;


use QATools\QATools\BEM\Element\Block;

class BlockChild extends Block
{

	/**
	 * Example property.
	 *
	 * @var string
	 */
	public $existingProperty = 'value';

	/**
	 * Proxies read access for properties to the sub-object.
	 *
	 * @param string $property Property to proxy.
	 *
	 * @return mixed
	 * @throws \InvalidArgumentException When given method doesn't exist.
	 */
	public function __get($property)
	{
		if ( $property === 'dynamicProperty' ) {
			return $this->existingProperty;
		}
		elseif ( $property === 'dynamicExceptionalProperty' ) {
			$this->exceptionalMethod();

			return null;
		}

		throw new \InvalidArgumentException('The "' . $property . '" doesn\'t exist.');
	}

	/**
	 * Proxies write access for properties to the sub-object.
	 *
	 * @param string $property Property to proxy.
	 * @param mixed  $value    Property value.
	 *
	 * @return void
	 * @throws \InvalidArgumentException When given method doesn't exist.
	 */
	public function __set($property, $value)
	{
		if ( $property === 'dynamicProperty' ) {
			$this->existingProperty = $value;

			return;
		}
		elseif ( $property === 'dynamicExceptionalProperty' ) {
			$this->exceptionalMethod();

			return;
		}

		throw new \InvalidArgumentException('The "' . $property . '" doesn\'t exist.');
	}

	/**
	 * Example of dynamic method.
	 *
	 * @param string $method    Method to proxy.
	 * @param array  $arguments Method arguments.
	 *
	 * @return mixed
	 * @throws \InvalidArgumentException When given method doesn't exist.
	 */
	public function __call($method, array $arguments)
	{
		if ( $method === 'dynamicMethod' ) {
			return 'OK';
		}
		elseif ( $method == 'dynamicExceptionalMethod' ) {
			$this->exceptionalMethod();
		}

		throw new \InvalidArgumentException('The "' . $method . '" doesn\'t exist.');
	}

	/**
	 * Method, that always throws an exception.
	 *
	 * @return void
	 * @throws \RuntimeException Always.
	 */
	public function exceptionalMethod()
	{
		throw new \RuntimeException('The exception.');
	}

}
