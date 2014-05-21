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

class ElementContainerProxyTest extends WebElementProxyTest
{

	const ELEMENT_CLASS = '\\tests\\aik099\\QATools\\PageObject\\Fixture\\Element\\ElementContainerChild';

	/**
	 * Occurs before "setUp" method is finished configuration jobs.
	 *
	 * @return void
	 */
	protected function beforeSetUpFinish()
	{
		parent::beforeSetUpFinish();

		$this->pageFactory->shouldReceive('initElementContainer')->andReturn($this->pageFactory);

		$decorator = m::mock('\\aik099\\QATools\\PageObject\\PropertyDecorator\\IPropertyDecorator');
		$this->pageFactory->shouldReceive('createDecorator')->andReturn($decorator);
		$this->pageFactory->shouldReceive('initElements')->andReturn($this->pageFactory);
	}

	public function testDefaultClassName()
	{
		$this->assertInstanceOf(self::ELEMENT_CLASS, $this->element->getObject());
	}

	public function testGetPageFactory()
	{
		$object = $this->element->getObject();

		$method = new \ReflectionMethod(get_class($object), 'getPageFactory');
		$method->setAccessible(true);

		$this->assertSame($this->pageFactory, $method->invoke($object));
	}

	/**
	 * Creates a proxy.
	 *
	 * @return WebElementProxy
	 */
	protected function createElement()
	{
		/** @var WebElementProxy $proxy */
		$proxy = new $this->collectionClass($this->locator, $this->pageFactory);
		$proxy->setClassName(self::ELEMENT_CLASS);

		return $proxy;
	}

}
