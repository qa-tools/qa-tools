<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\HtmlElements\Fixture\Element;


use QATools\QATools\HtmlElements\Element\AbstractTypifiedElementCollection;

class TypifiedElementCollectionChild extends AbstractTypifiedElementCollection
{

	/**
	 * Initializes collection with a list of elements.
	 *
	 * @param array $elements Elements.
	 */
	public function __construct(array $elements = array())
	{
		// Without this attempt to call "fromNodeElements" method from proxy test will fail.
		$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\TextInput';

		parent::__construct($elements);
	}

	/**
	 * Method for testing proxy abilities.
	 *
	 * @return integer
	 */
	public function proxyMe()
	{
		return 1;
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

		throw new \InvalidArgumentException('The "' . $method . '" doesn\'t exist.');
	}

}
