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
		'testGetObjectEmptyLocator', 'testIsValidSubstitute',
		'testSetName', 'testFromNodeElements', 'testInternalPointerPointingOnFirstElement',
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

	/**
	 * Sets expectation for a locator call with 2 resulting node elements.
	 *
	 * @return void
	 */
	protected function expectLocatorCallReturningTwoNodeElements()
	{
		$node_elements = array(
			$this->createNodeElement('XPATH1'),
			$this->createNodeElement('XPATH2'),
		);

		foreach ( $node_elements as $node_element ) {
			$this->selectorsHandler
				->shouldReceive('selectorToXpath')
				->with('se', array('xpath' => $node_element->getXPath()))
				->andReturn($node_element->getXPath());
		}

		$this->locator->shouldReceive('findAll')->once()->andReturn($node_elements);
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
