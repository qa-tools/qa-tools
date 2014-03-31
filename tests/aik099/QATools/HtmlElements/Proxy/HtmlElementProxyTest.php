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


use aik099\QATools\HtmlElements\Proxy\TypifiedElementProxy;
use Mockery as m;

class HtmlElementProxyTest extends TypifiedElementProxyTest
{

	const ELEMENT_CLASS = '\\tests\\aik099\\QATools\\HtmlElements\\Fixture\\Element\\HtmlElementChild';

	/**
	 * Occurs before "setUp" method is finished configuration jobs.
	 *
	 * @return void
	 */
	protected function beforeSetUpFinish()
	{
		$this->pageFactory->shouldReceive('initHtmlElement')->andReturn($this->pageFactory);

		$decorator = m::mock('\\aik099\\QATools\\PageObject\\PropertyDecorator\\IPropertyDecorator');
		$this->pageFactory->shouldReceive('createDecorator')->andReturn($decorator);
		$this->pageFactory->shouldReceive('initElements')->andReturn($this->pageFactory);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetPageFactory()
	{
		$object = $this->proxy->getObject();

		$method = new \ReflectionMethod(get_class($object), 'getPageFactory');
		$method->setAccessible(true);

		$this->assertSame($this->pageFactory, $method->invoke($object));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testDefaultClassName()
	{
		$expected = self::ELEMENT_CLASS;

		$this->assertInstanceOf($expected, $this->proxy->getObject());
	}

	/**
	 * Creates a proxy.
	 *
	 * @return TypifiedElementProxy
	 */
	protected function createProxy()
	{
		/** @var TypifiedElementProxy $proxy */
		$proxy = new $this->proxyClass($this->locator, $this->pageFactory, 'sample-name');
		$proxy->setClassName(self::ELEMENT_CLASS);

		return $proxy;
	}

}
