<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\PageObject\Proxy;


use aik099\QATools\PageObject\Proxy\WebElementProxy;
use Mockery as m;

class WebElementCollectionProxyTest extends WebElementProxyTest
{

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testDefaultClassName()
	{
		$expected = '\\aik099\\QATools\\PageObject\\Element\\WebElementCollection';

		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testMethodForwardingSuccess()
	{
		$this->assertEquals(1, $this->element->proxyMe());
	}

	/**
	 * Creates a proxy.
	 *
	 * @return WebElementProxy
	 */
	protected function createElement()
	{
		/** @var WebElementProxy $proxy */
		$proxy = new $this->collectionClass($this->locator, $this->pageFactory);
		$proxy->setClassName('\\tests\\aik099\\QATools\\PageObject\\Fixture\\Element\\WebElementCollectionChild');

		return $proxy;
	}

}
