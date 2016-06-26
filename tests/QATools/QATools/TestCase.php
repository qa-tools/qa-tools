<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools;


use Behat\Mink\Element\NodeElement;
use Mockery as m;
use QATools\QATools\PageObject\IPageFactory;

class TestCase extends \PHPUnit_Framework_TestCase
{

	/**
	 * Session.
	 *
	 * @var \Mockery\MockInterface
	 */
	protected $session;

	/**
	 * Session driver.
	 *
	 * @var \Mockery\MockInterface
	 */
	protected $driver;

	/**
	 * Selectors handler.
	 *
	 * @var \Mockery\MockInterface
	 */
	protected $selectorsHandler;

	/**
	 * Page factory.
	 *
	 * @var IPageFactory
	 */
	protected $pageFactory;

	protected function setUp()
	{
		parent::setUp();

		$handler = m::mock('\\Behat\\Mink\\Selector\\SelectorsHandler');
		$handler->shouldReceive('selectorToXpath')->with('se', array('xpath' => 'XPATH'))->andReturn('XPATH');
		$handler->shouldReceive('selectorToXpath')->with('se', array('xpath' => 'XPATH_ROOT'))->andReturn('/XPATH');
		$this->selectorsHandler = $handler;

		$this->driver = m::mock('\\Behat\\Mink\\Driver\\DriverInterface');

		$this->session = m::mock('\\Behat\\Mink\\Session');
		$this->session->shouldReceive('getSelectorsHandler')->andReturn($this->selectorsHandler);
		$this->session->shouldReceive('getDriver')->andReturn($this->driver)->byDefault();

		$this->pageFactory = m::mock('\\QATools\\QATools\\PageObject\\IPageFactory');
		$this->pageFactory->shouldReceive('getSession')->andReturn($this->session);
	}

	/**
	 * Mocks getTagName in the driver.
	 *
	 * @param string $tag_name Mocked return value.
	 *
	 * @return void
	 */
	protected function expectDriverGetTagName($tag_name, $xpath = 'XPATH')
	{
		$this->driver->shouldReceive('getTagName')->with($xpath)->andReturn($tag_name)->byDefault();
	}

	/**
	 * Mocks getAttribute in the driver.
	 *
	 * @param array $attributes Mocked attributes.
	 *
	 * @return void
	 */
	protected function expectDriverGetAttribute(array $attributes, $xpath = 'XPATH')
	{
		foreach ( $attributes as $attribute => $value ) {
			$this->driver->shouldReceive('getAttribute')->with($xpath, $attribute)->andReturn($value)->byDefault();
		}
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
		if ( !isset($xpath) ) {
			$xpath = 'XPATH';
		}

		return new NodeElement($xpath, $this->session);
	}

}
