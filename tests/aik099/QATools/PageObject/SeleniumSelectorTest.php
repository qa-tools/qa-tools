<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\PageObject;


use Behat\Mink\Selector\SelectorsHandler;
use aik099\QATools\PageObject\How;
use aik099\QATools\PageObject\SeleniumSelector;

class SeleniumSelectorTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Selenium Selector fixture.
	 *
	 * @var SeleniumSelector
	 */
	protected $selector;

	/**
	 * Sets up a Selenium Selector fixture.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$selectors_handler = new SelectorsHandler();

		$this->selector = new SeleniumSelector($selectors_handler);
	}

	/**
	 * Testing not implemented selectors.
	 *
	 * @param array $locator Locator.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\PageObject\Exception\WebElementException
	 * @dataProvider notImplementedDataProvider
	 */
	public function testNotImplemented(array $locator)
	{
		$this->selector->translateToXPath($locator);
	}

	/**
	 * Returns locators, that are not yet implemented.
	 *
	 * @return array
	 */
	public function notImplementedDataProvider()
	{
		return array(
			array(
				array(How::LINK_TEXT => ''),
			),
			array(
				array(How::PARTIAL_LINK_TEXT => ''),
			),
			array(
				array('what-the-heck' => ''),
			),
		);
	}

	/**
	 * Testing logic, behind locator into xpath transformation.
	 *
	 * @param array  $locator        Locator.
	 * @param string $expected_xpath Expected xpath.
	 *
	 * @return void
	 * @dataProvider correctDataProvider
	 */
	public function testCorrect(array $locator, $expected_xpath)
	{
		$this->assertEquals($expected_xpath, $this->selector->translateToXPath($locator));
	}

	/**
	 * Returns correct locator transformation results.
	 *
	 * @return array
	 */
	public function correctDataProvider()
	{
		return array(
			array(
				array(How::CLASS_NAME => 'sample-class'),
				"descendant-or-self::*[@class and contains(concat(' ', normalize-space(@class), ' '), ' sample-class ')]",
			),
			array(
				array(How::CSS => 'body > ul'),
				'descendant-or-self::body/ul',
			),
			array(
				array(How::ID => 'test[element]'),
				"descendant-or-self::*[@id = 'test[element]']",
			),
			array(
				array(How::NAME => 'section-title[arrow]'),
				"descendant-or-self::*[@name = 'section-title[arrow]']",
			),
			array(
				array(How::TAG_NAME => 'test-tag'),
				'descendant-or-self::test-tag',
			),
			array(
				array(How::XPATH => '_return-as-is_'),
				'_return-as-is_',
			),
		);
	}

	/**
	 * Testing incorrect locators.
	 *
	 * @param mixed $locator Locator.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\PageObject\Exception\WebElementException
	 * @dataProvider incorrectDataProvider
	 */
	public function testIncorrect($locator)
	{
		$this->selector->translateToXPath($locator);
	}

	/**
	 * Returns locators, in incorrect format.
	 *
	 * @return array
	 */
	public function incorrectDataProvider()
	{
		return array(
			array(''),
			array('//html'),
		);
	}

}
