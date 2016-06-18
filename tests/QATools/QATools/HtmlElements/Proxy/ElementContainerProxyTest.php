<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\HtmlElements\Proxy;


use QATools\QATools\HtmlElements\Proxy\TypifiedElementProxy;
use Mockery as m;

class ElementContainerProxyTest extends TypifiedElementProxyTest
{

	const ELEMENT_CLASS = '\\tests\\QATools\\QATools\\HtmlElements\\Fixture\\Element\\ElementContainerChild';

	/**
	 * Occurs before "setUp" method is finished configuration jobs.
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
	 * @return TypifiedElementProxy
	 */
	protected function createElement($replace_element_class = true)
	{
		/** @var TypifiedElementProxy $proxy */
		$proxy = new $this->collectionClass($this->locator, $this->pageFactory, 'sample-name');

		if ( $replace_element_class ) {
			$proxy->setClassName(self::ELEMENT_CLASS);
		}

		return $proxy;
	}

}
