<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\PageObject\Proxy;


use Mockery as m;

class WebElementProxyTest extends AbstractProxyTestCase
{

	/**
	 * Creates proxy.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\aik099\\QATools\\PageObject\\Proxy\\WebElementProxy';
			$this->collectionElementClass = '\\aik099\\QATools\\PageObject\\Element\\IWebElement';
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
		$expected = '\\aik099\\QATools\\PageObject\\Element\\WebElement';

		$this->assertInstanceOf($expected, $this->element->getObject());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetClassName()
	{
		$expected = '\\aik099\\QATools\\PageObject\\Element\\WebElement';

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
		$this->assertInstanceOf('\\aik099\\QATools\\PageObject\\Element\\IWebElement', $this->element);
	}

}
