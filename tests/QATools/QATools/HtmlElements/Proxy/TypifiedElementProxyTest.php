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


use Mockery as m;
use QATools\QATools\HtmlElements\Proxy\TypifiedElementProxy;
use tests\QATools\QATools\PageObject\Proxy\AbstractProxyTestCase;

class TypifiedElementProxyTest extends AbstractProxyTestCase
{

	protected function setUp()
	{
		$this->ignoreLocatorTests[] = 'testGetName';

		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\QATools\\QATools\\HtmlElements\\Proxy\\TypifiedElementProxy';
			$this->collectionElementClass = '\\QATools\\QATools\\HtmlElements\\Element\\ITypifiedElement';
		}

		parent::setUp();
	}

	public function testDefaultClassName()
	{
		$expected = '\\QATools\\QATools\\HtmlElements\\Element\\TextBlock';

		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	public function testSetClassName()
	{
		$expected = '\\tests\\QATools\\QATools\\HtmlElements\\Fixture\\Element\\ButtonChild';

		$this->expectDriverGetTagName('button');
		$this->expectDriverGetAttribute(array('type' => 'submit'));

		$this->element->setClassName($expected);
		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	public function testIsValidSubstitute()
	{
		$this->assertInstanceOf('\\QATools\\QATools\\HtmlElements\\Element\\ITypifiedElement', $this->element);
	}

	public function testGetName()
	{
		$this->assertEquals('sample-name', $this->element->getName());
	}

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
