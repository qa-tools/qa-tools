<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject;


use Behat\Mink\Selector\SelectorsHandler;
use QATools\QATools\PageObject\Exception\ElementException;
use QATools\QATools\PageObject\How;
use QATools\QATools\PageObject\SeleniumSelector;

class SeleniumSelectorTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Selenium Selector fixture.
	 *
	 * @var SeleniumSelector
	 */
	protected $selector;

	protected function setUp()
	{
		$selectors_handler = new SelectorsHandler();

		$this->selector = new SeleniumSelector($selectors_handler);
	}

	/**
	 * @dataProvider notImplementedDataProvider
	 */
	public function testNotImplemented(array $locator)
	{
		$this->setExpectedException(
			'QATools\\QATools\\PageObject\\Exception\\ElementException',
			'Selector type "' . key($locator) . '" not yet implemented',
			ElementException::TYPE_UNKNOWN_SELECTOR
		);

		$this->selector->translateToXPath($locator);
	}

	public function notImplementedDataProvider()
	{
		return array(
			array(
				array('what-the-heck' => ''),
			),
		);
	}

	/**
	 * Testing logic, behind locator into xpath transformation.
	 *
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
			How::CLASS_NAME => array(
				array(How::CLASS_NAME => 'sample-class'),
				"descendant-or-self::*[@class and contains(concat(' ', normalize-space(@class), ' '), ' sample-class ')]",
			),
			How::CSS => array(
				array(How::CSS => 'body > ul'),
				'descendant-or-self::body/ul',
			),
			How::ID => array(
				array(How::ID => 'test[element]'),
				"descendant-or-self::*[@id = 'test[element]']",
			),
			How::NAME => array(
				array(How::NAME => 'section-title[arrow]'),
				"descendant-or-self::*[@name = 'section-title[arrow]']",
			),
			How::ID_OR_NAME => array(
				array(How::ID_OR_NAME => 'section-title[arrow]'),
				"descendant-or-self::*[@id = 'section-title[arrow]' or @name = 'section-title[arrow]']",
			),
			How::LINK_TEXT => array(
				array(How::LINK_TEXT => 'cheese'),
				"descendant-or-self::a[./@href][normalize-space(string(.)) = 'cheese']",
			),
			How::PARTIAL_LINK_TEXT => array(
				array(How::PARTIAL_LINK_TEXT => 'cheese'),
				"descendant-or-self::a[./@href][contains(normalize-space(string(.)), 'cheese')]",
			),
			How::TAG_NAME => array(
				array(How::TAG_NAME => 'test-tag'),
				'descendant-or-self::test-tag',
			),
			How::XPATH => array(
				array(How::XPATH => '_return-as-is_'),
				'_return-as-is_',
			),
		);
	}

	/**
	 * Testing incorrect locators.
	 *
	 * @expectedException \QATools\QATools\PageObject\Exception\ElementException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\ElementException::TYPE_INCORRECT_SELECTOR
	 * @expectedExceptionMessage Incorrect Selenium selector format
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
