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


use Mockery as m;

class WebElementProxyTest extends AbstractProxyTestCase
{

	protected function setUp()
	{
		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\QATools\\QATools\\PageObject\\Proxy\\WebElementProxy';
			$this->collectionElementClass = '\\QATools\\QATools\\PageObject\\Element\\IWebElement';
		}

		parent::setUp();
	}

	public function testDefaultClassName()
	{
		$expected = '\\QATools\\QATools\\PageObject\\Element\\WebElement';

		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	public function testSetClassName()
	{
		$expected = '\\QATools\\QATools\\PageObject\\Element\\WebElement';

		$this->element->setClassName($expected);
		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	public function testIsValidSubstitute()
	{
		$this->assertInstanceOf('\\QATools\\QATools\\PageObject\\Element\\IWebElement', $this->element);
	}

	public function testInternalPointerPointingOnFirstElement()
	{
		$this->expectLocatorCallReturningTwoNodeElements();

		$proxy = $this->createElement();

		$this->assertCount(2, $proxy);
		$this->assertEquals('XPATH1', $proxy->getObject()->getXPath());
	}

}
