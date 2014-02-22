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


use aik099\QATools\BEM\Proxy\BlockProxy;
use Mockery as m;
use tests\aik099\QATools\PageObject\Proxy\AbstractProxyTestCase;

class BlockProxyTest extends AbstractProxyTestCase
{

	/**
	 * Creates proxy.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->ignoreLocatorTests[] = 'testGetName';
		$this->proxyClass = '\\aik099\\QATools\\BEM\\Proxy\\BlockProxy';
		$this->locatorClass = '\\aik099\\QATools\\BEM\\ElementLocator\\BEMElementLocator';

		parent::setUp();
	}

	/**
	 * Occurs before "setUp" method is finished configuration jobs.
	 *
	 * @return void
	 */
	protected function beforeSetUpFinish()
	{
		$decorator = m::mock('\\aik099\\QATools\\PageObject\\PropertyDecorator\\IPropertyDecorator');
		$this->pageFactory->shouldReceive('createDecorator')->andReturn($decorator);
		$this->pageFactory->shouldReceive('initElements')->andReturn($this->pageFactory);
	}

	/**
	 * Sets expectation for a specific locator call.
	 *
	 * @return void
	 */
	protected function expectLocatorCall()
	{
		$this->locator->shouldReceive('findAll')->once()->andReturn(array($this->createNodeElement()));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testDefaultClassName()
	{
		$expected = '\\aik099\\QATools\\BEM\\Element\\Block';

		$this->assertInstanceOf($expected, $this->proxy->getObject());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetClassName()
	{
		$expected = '\\tests\\aik099\\QATools\\BEM\\Fixture\\Element\\BlockChild';

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
		$this->assertInstanceOf('\\aik099\\QATools\\BEM\\Element\\IBlock', $this->proxy);
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
		$this->assertInternalType('array', $this->proxy->getNodes());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\PageObject\Exception\ElementNotFoundException
	 */
	public function testGetObjectEmptyLocator()
	{
		$this->locator->shouldReceive('findAll')->once()->andReturn(null);
		$this->locator->shouldReceive('__toString')->once()->andReturn('OK');

		$this->createProxy()->getObject();
	}

	/**
	 * Creates a proxy.
	 *
	 * @return BlockProxy
	 */
	protected function createProxy()
	{
		return new $this->proxyClass('sample-name', $this->locator, $this->pageFactory);
	}

}
