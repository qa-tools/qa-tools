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


use Behat\Mink\Element\ElementFinder;
use Behat\Mink\Element\NodeElement;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use QATools\QATools\PageObject\IPageFactory;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectException;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectExceptionMessageMatches;

class TestCase extends \PHPUnit\Framework\TestCase
{

	use MockeryPHPUnitIntegration, ExpectException, ExpectExceptionMessageMatches;

	/**
	 * Session.
	 *
	 * @var \Mockery\MockInterface
	 */
	protected $session;

	/**
	 * Element finder.
	 *
	 * @var \Mockery\MockInterface
	 */
	protected $elementFinder;

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

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		$handler = m::mock('\\Behat\\Mink\\Selector\\SelectorsHandler');
		$this->selectorsHandler = $handler;

		$this->session = m::mock('\\Behat\\Mink\\Session');
		$this->session->shouldReceive('getSelectorsHandler')->andReturn($this->selectorsHandler);

		$this->driver = m::mock('\\Behat\\Mink\\Driver\\DriverInterface');
		$this->session->shouldReceive('getDriver')->andReturn($this->driver)->byDefault();

		if ( \class_exists('\\Behat\\Mink\\Element\\ElementFinder') ) {
			$this->elementFinder = m::mock('\\Behat\\Mink\\Element\\ElementFinder');
			$this->session->shouldReceive('getElementFinder')->andReturn($this->elementFinder)->byDefault();
		}

		if ( $this->usesNewMinkArchitecture() ) {
			$page = new \Behat\Mink\Element\DocumentElement($this->driver, $this->elementFinder);
		}
		else {
			$page = new \Behat\Mink\Element\DocumentElement($this->session);
		}

		$this->session->shouldReceive('getPage')->andReturn($page)->byDefault();

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

		if ( $this->usesNewMinkArchitecture() ) {
			return new NodeElement($xpath, $this->driver, $this->elementFinder);
		}

		return new NodeElement($xpath, $this->session);
	}

	/**
	 * Detects, if the installed Mink version uses the Driver + ElementFinder based Element hierarchy
	 * (e.g. the "2-architecture-changes" branch) instead of the Session-based one.
	 *
	 * The presence of the "\Behat\Mink\Element\ElementFinder" class alone isn't a reliable indicator,
	 * because that class was already present in some 1.x releases (e.g. v1.13.0), while
	 * "Element::__construct()" still accepted a "Session" back then.
	 *
	 * @return boolean
	 */
	protected function usesNewMinkArchitecture()
	{
		$constructor = new \ReflectionMethod('\\Behat\\Mink\\Element\\DocumentElement', '__construct');
		$first_parameter_type = $constructor->getParameters()[0]->getType();

		return $first_parameter_type && $first_parameter_type->getName() === 'Behat\\Mink\\Driver\\DriverInterface';
	}

}
