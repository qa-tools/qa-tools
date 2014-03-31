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


use aik099\QATools\PageObject\Proxy\WebElementProxy;
use Mockery as m;

class HtmlElementProxyTest extends WebElementProxyTest
{

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
	 * Creates a proxy.
	 *
	 * @return WebElementProxy
	 */
	protected function createProxy()
	{
		/** @var WebElementProxy $proxy */
		$proxy = new $this->proxyClass($this->locator, $this->pageFactory);
		$proxy->setClassName('\\tests\\aik099\\QATools\\PageObject\\Fixture\\Element\\HtmlElementChild');

		return $proxy;
	}

}
