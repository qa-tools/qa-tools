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


use QATools\QATools\BEM\Element\Element;

class ElementChild extends Element
{

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
