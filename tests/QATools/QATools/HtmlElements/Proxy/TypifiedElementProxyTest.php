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

	const ELEMENT_CLASS = '\\tests\\QATools\\QATools\\HtmlElements\\Fixture\\Element\\TypifiedElementChild';

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		$this->ignoreLocatorTests[] = 'testGetName';
		$this->ignoreLocatorTests[] = 'testDefaultClassName';

		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\QATools\\QATools\\HtmlElements\\Proxy\\TypifiedElementProxy';
			$this->collectionElementClass = '\\QATools\\QATools\\HtmlElements\\Element\\ITypifiedElement';
		}

		parent::setUpTest();
	}

	public function testDefaultClassName()
	{
		$this->markTestSkipped(
			'The "' . get_class($this->element) . '" proxy is interface based and has no default class.'
		);
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

	public function testInternalPointerPointingOnFirstElement()
	{
		$this->expectLocatorCallReturningTwoNodeElements();

		$proxy = $this->createElement();

		$this->assertCount(2, $proxy);
		$this->assertEquals('XPATH1', $proxy->getObject()->getXPath());
	}

	/**
	 * Creates a proxy.
	 *
	 * @param boolean $replace_element_class Replace element class.
	 *
	 * @return TypifiedElementProxy
	 */
	protected function createElement($replace_element_class = true)
	{
		/** @var TypifiedElementProxy $proxy */
		$proxy = new $this->collectionClass($this->locator, $this->pageFactory, 'sample-name');

		if ( $replace_element_class ) {
			$proxy->setClassName(self::ELEMENT_CLASS);
		}

		return $proxy;
	}

}
