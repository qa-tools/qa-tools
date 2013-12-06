<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\PageObject;


use aik099\QATools\PageObject\WebElementProxy;
use Mockery as m;
use tests\aik099\QATools\TestCase;

class WebElementProxyTest extends TestCase
{

	/**
	 * Proxy class.
	 *
	 * @var string
	 */
	protected $proxyClass = '\\aik099\\QATools\\PageObject\\WebElementProxy';

	/**
	 * Proxy.
	 *
	 * @var WebElementProxy
	 */
	protected $proxy;

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
	protected $ignoreLocatorTests = array('testSetContainer', 'testGetContainerFallback', 'testGetObjectEmptyLocator');

	/**
	 * Creates proxy.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->locator = m::mock('\\aik099\\QATools\\PageObject\\ElementLocators\\IElementLocator');

		if ( !in_array($this->getName(), $this->ignoreLocatorTests) ) {
			$this->locator->shouldReceive('find')->once()->andReturn($this->createNodeElement());
		}

		$this->proxy = $this->createProxy();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testDefaultClassName()
	{
		$expected = '\\aik099\\QATools\\PageObject\\Elements\\WebElement';

		$this->assertInstanceOf($expected, $this->proxy->getObject());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetClassName()
	{
		$expected = '\\aik099\\QATools\\PageObject\\Elements\\WebElement';

		$this->proxy->setClassName($expected);
		$this->assertInstanceOf($expected, $this->proxy->getObject());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetObjectSharing()
	{
		$this->assertSame($this->proxy->getObject(), $this->proxy->getObject());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\PageObject\Exceptions\PageFactoryException
	 */
	public function testGetObjectEmptyLocator()
	{
		$this->locator->shouldReceive('find')->once()->andReturn(null);
		$this->locator->shouldReceive('__toString')->once()->andReturn('OK');

		$this->createProxy()->getObject();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testMethodForwardingSuccess()
	{
		$this->assertEquals('XPATH', $this->proxy->getXpath());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\PageObject\Exceptions\PageFactoryException
	 */
	public function testMethodForwardingFailure()
	{
		$this->proxy->nonExistingMethod();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetContainer()
	{
		$container = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');

		$this->assertSame($this->proxy, $this->proxy->setContainer($container));
		$this->assertSame($container, $this->proxy->getContainer());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetContainerFallback()
	{
		$this->assertNull($this->proxy->getContainer());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testContainerToElement()
	{
		$container = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');
		$this->proxy->setContainer($container);

		$this->assertSame($container, $this->proxy->getObject()->getContainer());
	}

	/**
	 * Creates a proxy.
	 *
	 * @return WebElementProxy
	 */
	protected function createProxy()
	{
		return new $this->proxyClass($this->locator, $this->pageFactory);
	}

}
