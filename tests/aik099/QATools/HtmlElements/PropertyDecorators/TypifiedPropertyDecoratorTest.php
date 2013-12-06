<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\HtmlElements\PropertyDecorators;


use Mockery as m;
use aik099\QATools\PageObject\WebElementProxy;
use tests\aik099\QATools\PageObject\PropertyDecorators\DefaultPropertyDecoratorTest;

class TypifiedPropertyDecoratorTest extends DefaultPropertyDecoratorTest
{

	/**
	 * Prepares page.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->decoratorClass = '\\aik099\\QATools\\HtmlElements\\PropertyDecorators\\TypifiedPropertyDecorator';

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @param string $element_class Element class.
	 * @param string $proxy_class   Proxy class.
	 *
	 * @return WebElementProxy
	 * @dataProvider proxyDataProvider
	 */
	public function testProxyWebElement($element_class, $proxy_class)
	{
		$this->property->shouldReceive('getAnnotationsFromPropertyOrClass')->with('@element-name')->andReturn(array());
		$this->property->shouldReceive('__toString')->andReturn('PROP_NAME');

		$proxy = parent::testProxyWebElement($element_class, $proxy_class);

		if ( strpos($proxy_class, 'Typified') !== false ) {
			$this->assertEquals('PROP_NAME', $proxy->getName());
			$this->assertEquals('PROP_NAME', $proxy->getObject()->getName());
		}

		return $proxy;
	}

	/**
	 * Provide test data for proxy.
	 *
	 * @return array
	 */
	public function proxyDataProvider()
	{
		$data = parent::proxyDataProvider();

		$data[] = array('\\aik099\\QATools\\HtmlElements\\Elements\\TextBlock', '\\aik099\\QATools\\HtmlElements\\TypifiedElementProxy');

		return $data;
	}

}
