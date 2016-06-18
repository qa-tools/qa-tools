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


use QATools\QATools\BEM\Proxy\ElementProxy;
use Mockery as m;
use tests\QATools\QATools\PageObject\Proxy\AbstractProxyTestCase;

class ElementProxyTest extends AbstractProxyTestCase
{

	protected function setUp()
	{
		$this->ignoreLocatorTests[] = 'testGetName';
		$this->locatorClass = '\\QATools\\QATools\\BEM\\ElementLocator\\BEMElementLocator';

		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\QATools\\QATools\\BEM\\Proxy\\ElementProxy';
			$this->collectionElementClass = '\\QATools\\QATools\\BEM\\Element\\IElement';
		}

		parent::setUp();
	}

	public function testDefaultClassName()
	{
		$expected = '\\QATools\\QATools\\BEM\\Element\\Element';

		$this->assertInstanceOf($expected, $this->createElement(false)->getObject());
	}

	public function testSetClassName()
	{
		$expected = '\\tests\\QATools\\QATools\\BEM\\Fixture\\Element\\ElementChild';

		$this->element->setClassName($expected);
		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	public function testIsValidSubstitute()
	{
		$this->assertInstanceOf('\\QATools\\QATools\\BEM\\Element\\IElement', $this->element);
	}

	public function testGetName()
	{
		$this->assertEquals('sample-name', $this->element->getName());
	}

	public function testMethodForwardingSuccess()
	{
		$this->assertInstanceOf(
			'\\QATools\\QATools\\PageObject\\Element\\IWebElement',
			$this->element->getWrappedElement()
		);
	}

	/**
	 * Creates a proxy.
	 *
	 * @param boolean $replace_element_class Replace element class.
	 *
	 * @return ElementProxy
	 */
	protected function createElement($replace_element_class = true)
	{
		return new $this->collectionClass('sample-name', $this->locator, $this->pageFactory);
	}

}
