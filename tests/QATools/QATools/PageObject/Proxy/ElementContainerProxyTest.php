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

class ElementContainerProxyTest extends WebElementProxyTest
{

	const ELEMENT_CLASS = '\\tests\\QATools\\QATools\\PageObject\\Fixture\\Element\\ElementContainerChild';

	/**
	 * Occurs before "setUpTest" method is finished configuration jobs.
	 *
	 * @return void
	 */
	protected function beforeSetUpFinish()
	{
		parent::beforeSetUpFinish();

		$this->pageFactory->shouldReceive('initElementContainer')->andReturn($this->pageFactory);

		$decorator = m::mock('\\QATools\\QATools\\PageObject\\PropertyDecorator\\IPropertyDecorator');
		$this->pageFactory->shouldReceive('createDecorator')->andReturn($decorator);
		$this->pageFactory->shouldReceive('initElements')->andReturn($this->pageFactory);
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
	 * @param boolean $replace_element_class Replace element class.
	 *
	 * @return WebElementProxy
	 */
	protected function createElement($replace_element_class = true)
	{
		/** @var WebElementProxy $proxy */
		$proxy = new $this->collectionClass($this->locator, $this->pageFactory);

		if ( $replace_element_class ) {
			$proxy->setClassName(self::ELEMENT_CLASS);
		}

		return $proxy;
	}

}
