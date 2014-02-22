<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\BEM\Proxy;


use aik099\QATools\BEM\Proxy\ElementProxy;
use Mockery as m;
use tests\aik099\QATools\PageObject\Proxy\AbstractProxyTestCase;

class ElementProxyTest extends AbstractProxyTestCase
{

	/**
	 * Creates proxy.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->ignoreLocatorTests[] = 'testGetName';
		$this->proxyClass = '\\aik099\\QATools\\BEM\\Proxy\\ElementProxy';
		$this->locatorClass = '\\aik099\\QATools\\BEM\\ElementLocator\\BEMElementLocator';

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testDefaultClassName()
	{
		$expected = '\\aik099\\QATools\\BEM\\Element\\Element';

		$this->assertInstanceOf($expected, $this->proxy->getObject());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetClassName()
	{
		$expected = '\\tests\\aik099\\QATools\\BEM\\Fixture\\Element\\ElementChild';

		$this->proxy->setClassName($expected);
		$this->assertInstanceOf($expected, $this->proxy->getObject());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testIsValidSubstitute()
	{
		$this->assertInstanceOf('\\aik099\\QATools\\BEM\\Element\\IElement', $this->proxy);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetName()
	{
		$this->assertEquals('sample-name', $this->proxy->getName());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testMethodForwardingSuccess()
	{
		$this->assertInstanceOf('\\aik099\\QATools\\PageObject\\Element\\IWebElement', $this->proxy->getWrappedElement());
	}

	/**
	 * Creates a proxy.
	 *
	 * @return ElementProxy
	 */
	protected function createProxy()
	{
		return new $this->proxyClass('sample-name', $this->locator, $this->pageFactory);
	}

}
