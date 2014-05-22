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

	protected function setUp()
	{
		$this->ignoreLocatorTests[] = 'testGetName';
		$this->locatorClass = '\\aik099\\QATools\\BEM\\ElementLocator\\BEMElementLocator';

		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\aik099\\QATools\\BEM\\Proxy\\BlockProxy';
			$this->collectionElementClass = '\\aik099\\QATools\\BEM\\Element\\IBlock';
		}

		parent::setUp();
	}

	/**
	 * Occurs before "setUp" method is finished configuration jobs.
	 *
	 * @return void
	 */
	protected function beforeSetUpFinish()
	{
		parent::beforeSetUpFinish();

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

	public function testDefaultClassName()
	{
		$expected = '\\aik099\\QATools\\BEM\\Element\\Block';

		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	public function testSetClassName()
	{
		$expected = '\\tests\\aik099\\QATools\\BEM\\Fixture\\Element\\BlockChild';

		$this->element->setClassName($expected);
		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	public function testIsValidSubstitute()
	{
		$this->assertInstanceOf('\\aik099\\QATools\\BEM\\Element\\IBlock', $this->element);
	}

	public function testGetName()
	{
		$this->assertEquals('sample-name', $this->element->getName());
	}

	public function testMethodForwardingSuccess()
	{
		$this->assertInternalType('array', $this->element->getNodes());
	}

	/**
	 * @expectedException \aik099\QATools\PageObject\Exception\ElementNotFoundException
	 */
	public function testGetObjectEmptyLocator()
	{
		$this->locator->shouldReceive('findAll')->once()->andReturn(null);
		$this->locator->shouldReceive('__toString')->once()->andReturn('OK');

		$this->createElement()->getObject();
	}

	/**
	 * Creates a proxy.
	 *
	 * @return BlockProxy
	 */
	protected function createElement()
	{
		return new $this->collectionClass('sample-name', $this->locator, $this->pageFactory);
	}

}
