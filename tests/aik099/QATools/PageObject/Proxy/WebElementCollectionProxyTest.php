<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\aik099\QATools\PageObject\Proxy;


use aik099\QATools\PageObject\Proxy\WebElementProxy;
use Mockery as m;

class WebElementCollectionProxyTest extends WebElementProxyTest
{

	public function testDefaultClassName()
	{
		$expected = '\\aik099\\QATools\\PageObject\\Element\\WebElementCollection';

		$this->assertInstanceOf($expected, $this->element->getObject());
	}

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
