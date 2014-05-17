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

		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\aik099\\QATools\\HtmlElements\\Proxy\\TypifiedElementProxy';
			$this->collectionElementClass = '\\aik099\\QATools\\HtmlElements\\Element\\ITypifiedElement';
		}

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

		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetClassName()
	{
		$expected = '\\tests\\aik099\\QATools\\HtmlElements\\Fixture\\Element\\ButtonChild';

		$this->element->setClassName($expected);
		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testIsValidSubstitute()
	{
		$this->assertInstanceOf('\\aik099\\QATools\\HtmlElements\\Element\\ITypifiedElement', $this->element);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetName()
	{
		$this->assertEquals('sample-name', $this->element->getName());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetName()
	{
		$expected = 'OK';
		$this->assertSame($this->element, $this->element->setName($expected));
		$this->assertEquals($expected, $this->element->getName());
	}

	/**
	 * Creates a proxy.
	 *
	 * @return TypifiedElementProxy
	 */
	protected function createElement()
	{
		return new $this->collectionClass($this->locator, $this->pageFactory, 'sample-name');
	}

}
