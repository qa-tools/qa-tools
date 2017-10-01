<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\BEM\Proxy;


use QATools\QATools\BEM\Proxy\BlockProxy;
use Mockery as m;
use tests\QATools\QATools\PageObject\Proxy\AbstractProxyTestCase;

class BlockProxyTest extends AbstractProxyTestCase
{

	protected function setUp()
	{
		$this->ignoreLocatorTests[] = 'testGetName';
		$this->locatorClass = '\\QATools\\QATools\\BEM\\ElementLocator\\BEMElementLocator';

		if ( $this->collectionClass === null ) {
			$this->collectionClass = '\\QATools\\QATools\\BEM\\Proxy\\BlockProxy';
			$this->collectionElementClass = '\\QATools\\QATools\\BEM\\Element\\IBlock';
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

		$decorator = m::mock('\\QATools\\QATools\\PageObject\\PropertyDecorator\\IPropertyDecorator');
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
		$expected = '\\QATools\\QATools\\BEM\\Element\\Block';

		$this->assertInstanceOf($expected, $this->createElement(false)->getObject());
	}

	public function testSetClassName()
	{
		$expected = '\\tests\\QATools\\QATools\\BEM\\Fixture\\Element\\BlockChild';

		$this->element->setClassName($expected);
		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	public function testIsValidSubstitute()
	{
		$this->assertInstanceOf('\\QATools\\QATools\\BEM\\Element\\IBlock', $this->element);
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
	 * @expectedException \QATools\QATools\PageObject\Exception\ElementNotFoundException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\ElementNotFoundException::TYPE_NOT_FOUND
	 * @expectedExceptionMessage Block not found by selector: OK
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
	 * @param boolean $replace_element_class Replace element class.
	 *
	 * @return BlockProxy
	 */
	protected function createElement($replace_element_class = true)
	{
		/** @var BlockProxy $proxy */
		$proxy = new $this->collectionClass('sample-name', $this->locator, $this->pageFactory);

		if ( $replace_element_class ) {
			$proxy->setClassName('\\tests\\QATools\\QATools\\BEM\\Fixture\\Element\\BlockChild');
		}

		return $proxy;
	}

}
