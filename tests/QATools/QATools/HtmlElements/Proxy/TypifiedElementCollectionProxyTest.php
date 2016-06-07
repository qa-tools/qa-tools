<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\HtmlElements\Proxy;


use QATools\QATools\HtmlElements\Proxy\TypifiedElementProxy;
use Mockery as m;

class TypifiedElementCollectionProxyTest extends TypifiedElementProxyTest
{

	const ELEMENT_CLASS = '\\tests\\QATools\\QATools\\HtmlElements\\Fixture\\Element\\TypifiedElementCollectionChild';

	protected function setUp()
	{
		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\QATools\\QATools\\HtmlElements\\Proxy\\TypifiedElementCollectionProxy';
			$this->collectionElementClass = '\\QATools\\QATools\\HtmlElements\\Element\\TextInput';
		}

		parent::setUp();
	}

	protected function beforeSetUpFinish()
	{
		parent::beforeSetUpFinish();

		$this->expectDriverGetTagName('textarea');
	}

	public function testDefaultClassName()
	{
		$this->assertInstanceOf(self::ELEMENT_CLASS, $this->element->getObject());
	}

	public function testSetClassName()
	{
		$expected = '\\tests\\QATools\\QATools\\HtmlElements\\Fixture\\Element\\AlternateTypifiedElementCollection';

		$this->element->setClassName($expected);
		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	public function testMethodForwardingSuccess()
	{
		$this->assertEquals(1, $this->element->proxyMe());
	}

	public function testInternalPointerPointingOnFirstElement()
	{
		$node_elements = $this->expectLocatorCallReturningTwoNodeElements();

		foreach ( $node_elements as $node_element ) {
			$this->driver->shouldReceive('getTagName')->with($node_element->getXpath())->andReturn('textarea');
		}

		$this->assertEquals(1, $this->element->getObject()->proxyMe());
	}

	/**
	 * Creates a proxy.
	 *
	 * @return TypifiedElementProxy
	 */
	protected function createElement()
	{
		/** @var TypifiedElementProxy $proxy */
		$proxy = new $this->collectionClass($this->locator, $this->pageFactory, 'sample-name');
		$proxy->setClassName(self::ELEMENT_CLASS);

		return $proxy;
	}

}
