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


use QATools\QATools\PageObject\Proxy\WebElementProxy;
use Mockery as m;

class WebElementCollectionProxyTest extends WebElementProxyTest
{

	protected function setUp()
	{
		$this->collectionClass = '\\QATools\\QATools\\PageObject\\Proxy\\WebElementCollectionProxy';
		$this->collectionElementClass = '\\QATools\\QATools\\PageObject\\Element\\WebElement';

		parent::setUp();
	}

	public function testDefaultClassName()
	{
		$expected = '\\QATools\\QATools\\PageObject\\Element\\WebElementCollection';

		$this->assertInstanceOf($expected, $this->createElement(false)->getObject());
	}

	public function testMethodForwardingSuccess()
	{
		$this->assertEquals(1, $this->element->proxyMe());
	}

	public function testSetClassName()
	{
		$expected = '\\QATools\\QATools\\PageObject\\Element\\WebElementCollection';

		$this->element->setClassName($expected);
		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	public function testInternalPointerPointingOnFirstElement()
	{
		$this->expectLocatorCallReturningTwoNodeElements();

		$this->assertEquals(1, $this->element->getObject()->proxyMe());
	}

	/**
	 * Creates a proxy.
	 *
	 * @param boolean $replace_element_class Replace element class.
	 *
	 * @return WebElementProxy
	 */
	protected function createElement($replace_element_class = true)
	{
		/** @var WebElementProxy $proxy */
		$proxy = new $this->collectionClass($this->locator, $this->pageFactory);

		if ( $replace_element_class ) {
			$proxy->setClassName('\\tests\\QATools\\QATools\\PageObject\\Fixture\\Element\\WebElementCollectionChild');
		}

		return $proxy;
	}

}
