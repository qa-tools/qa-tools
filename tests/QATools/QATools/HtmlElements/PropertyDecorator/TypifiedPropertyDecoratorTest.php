<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\HtmlElements\PropertyDecorator;


use Behat\Mink\Element\NodeElement;
use Mockery as m;
use QATools\QATools\PageObject\Proxy\WebElementProxy;
use tests\QATools\QATools\PageObject\PropertyDecorator\DefaultPropertyDecoratorTest;

class TypifiedPropertyDecoratorTest extends DefaultPropertyDecoratorTest
{

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		$this->decoratorClass = '\\QATools\\QATools\\HtmlElements\\PropertyDecorator\\TypifiedPropertyDecorator';

		parent::setUpTest();
	}

	/**
	 * @return WebElementProxy
	 * @dataProvider proxyDataProvider
	 */
	public function testProxyWebElement($element_class, $proxy_class)
	{
		$this->property->shouldReceive('getAnnotationsFromPropertyOrClass')->with('@element-name')->andReturn(array());
		$this->property->shouldReceive('__toString')->andReturn('PROP_NAME');

		$proxy = parent::testProxyWebElement($element_class, $proxy_class);

		if ( strpos($proxy_class, 'Typified') !== false ) {
			$this->assertEquals('PROP_NAME', $proxy->getName(), 'Proxy name was set.');
			$this->assertEquals('PROP_NAME', $proxy->getObject()->getName(), 'Proxied object name was set.');

			if ( strpos($proxy_class, 'Collection') !== false ) {
				foreach ( $proxy->getObject() as $proxied_element ) {
					$this->assertEquals(
						'PROP_NAME',
						$proxied_element->getName(),
						'Each collection element name was set.'
					);
				}
			}
		}

		return $proxy;
	}

	/**
	 * Creates NodeElement mock.
	 *
	 * @param string|null $xpath XPath of the element.
	 *
	 * @return NodeElement
	 */
	protected function createNodeElement($xpath = null)
	{
		$element = parent::createNodeElement($xpath);

		if ( $this->getName(false) === 'testProxyWebElement' ) {
			$this->expectDriverGetTagName('input', $xpath);
			$this->expectDriverGetAttribute(array('type' => 'radio'), $xpath);
		}

		return $element;
	}

	public function proxyDataProvider()
	{
		$data = parent::proxyDataProvider();

		$data['typified element'] = array(
			'\\QATools\\QATools\\HtmlElements\\Element\\TextBlock',
			'\\QATools\\QATools\\HtmlElements\\Proxy\\TypifiedElementProxy',
		);
		$data['typified element collection'] = array(
			'\\QATools\\QATools\\HtmlElements\\Element\\RadioGroup',
			'\\QATools\\QATools\\HtmlElements\\Proxy\\TypifiedElementCollectionProxy',
		);

		return $data;
	}

}
