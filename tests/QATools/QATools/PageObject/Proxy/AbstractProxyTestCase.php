<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Proxy;


use QATools\QATools\PageObject\Proxy\AbstractProxy;
use Mockery as m;
use tests\QATools\QATools\PageObject\Element\AbstractElementCollectionTestCase;

abstract class AbstractProxyTestCase extends AbstractElementCollectionTestCase
{

	/**
	 * Locator class.
	 *
	 * @var string
	 */
	protected $locatorClass = '\\QATools\\QATools\\PageObject\\ElementLocator\\IElementLocator';

	/**
	 * Collection.
	 *
	 * @var AbstractProxy
	 */
	protected $element;

	/**
	 * Locator.
	 *
	 * @var \Mockery\MockInterface
	 */
	protected $locator;

	/**
	 * Test names, that are not using locator.
	 *
	 * @var array
	 */
	protected $ignoreLocatorTests = array(
		'testSetContainer', 'testGetContainerFallback', 'testGetObjectEmptyLocator', 'testIsValidSubstitute',
		'testSetName', 'testFromNodeElements',
	);

	protected function setUp()
	{
		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\QATools\\QATools\\PageObject\\Proxy\\AbstractProxy';
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
		$this->locator = m::mock($this->locatorClass);

		if ( !in_array($this->getName(), $this->ignoreLocatorTests) ) {
			$this->expectLocatorCall();
		}
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

	public function testGetObjectSharing()
	{
		$this->assertSame($this->element->getObject(), $this->element->getObject());
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\ElementNotFoundException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\ElementNotFoundException::TYPE_NOT_FOUND
	 * @expectedExceptionMessage No elements found by selector: OK
	 */
	public function testGetObjectEmptyLocator()
	{
		$this->locator->shouldReceive('findAll')->once()->andReturn(null);
		$this->locator->shouldReceive('__toString')->once()->andReturn('OK');

		$this->createElement()->getObject();
	}

	public function testMethodForwardingSuccess()
	{
		$this->assertEquals('XPATH', $this->element->getXpath());
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\ElementException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\ElementException::TYPE_UNKNOWN_METHOD
	 * @expectedExceptionMessage "nonExistingMethod" method is not available on the
	 */
	public function testMethodForwardingFailure()
	{
		$this->element->nonExistingMethod();
	}

	public function testSetContainer()
	{
		$container = m::mock('\\QATools\\QATools\\PageObject\\ISearchContext');

		$this->assertSame($this->element, $this->element->setContainer($container));
		$this->assertSame($container, $this->element->getContainer());
	}

	public function testGetContainerFallback()
	{
		$this->assertNull($this->element->getContainer());
	}

	public function testContainerToElement()
	{
		$container = m::mock('\\QATools\\QATools\\PageObject\\ISearchContext');
		$this->element->setContainer($container);

		$this->assertSame($container, $this->element->getObject()->getContainer());
	}

	abstract public function testDefaultClassName();

	abstract public function testSetClassName();

	abstract public function testIsValidSubstitute();

	/**
	 * Creates a proxy.
	 *
	 * @return AbstractProxy
	 */
	protected function createElement()
	{
		return new $this->collectionClass($this->locator, $this->pageFactory);
	}

}
