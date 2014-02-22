<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\HtmlElements\Proxy;


use Mockery as m;
use aik099\QATools\HtmlElements\Proxy\TypifiedElementProxy;
use tests\aik099\QATools\PageObject\Proxy\AbstractProxyTestCase;

class TypifiedElementProxyTest extends AbstractProxyTestCase
{

	/**
	 * Creates proxy.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->ignoreLocatorTests[] = 'testGetName';
		$this->proxyClass = '\\aik099\\QATools\\HtmlElements\\Proxy\\TypifiedElementProxy';

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testDefaultClassName()
	{
		$expected = '\\aik099\\QATools\\HtmlElements\\Element\\TextBlock';

		$this->assertInstanceOf($expected, $this->proxy->getObject());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetClassName()
	{
		$expected = '\\tests\\aik099\\QATools\\HtmlElements\\Fixture\\Element\\ButtonChild';

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
		$this->assertInstanceOf('\\aik099\\QATools\\HtmlElements\\Element\\ITypifiedElement', $this->proxy);
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
	 * Creates a proxy.
	 *
	 * @return TypifiedElementProxy
	 */
	protected function createProxy()
	{
		return new $this->proxyClass($this->locator, $this->pageFactory, 'sample-name');
	}

}
