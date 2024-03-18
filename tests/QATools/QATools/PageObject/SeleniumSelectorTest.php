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


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use QATools\QATools\PageObject\Exception\ElementException;
use QATools\QATools\PageObject\How;
use QATools\QATools\PageObject\SeleniumSelector;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectException;

class SeleniumSelectorTest extends TestCase
{

	use MockeryPHPUnitIntegration, ExpectException;

	/**
	 * Selenium Selector fixture.
	 *
	 * @var SeleniumSelector
	 */
	protected $selector;

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		$this->selector = new SeleniumSelector();
	}

	public function testNotImplemented()
	{
		$this->expectException('QATools\\QATools\\PageObject\\Exception\\ElementException');
		$this->expectExceptionMessage('The "not-implemented-selector" how is not yet implemented');
		$this->expectExceptionCode(ElementException::TYPE_UNKNOWN_SELECTOR);

		$this->selector->translateToXPath('not-implemented-selector', 'not empty');
	}

	/**
	 * Testing logic, behind locator into xpath transformation.
	 *
	 * @dataProvider correctDataProvider
	 */
	public function testCorrect($how, $using, $expected_count)
	{
		$dom = new \DOMDocument('1.0', 'UTF-8');

		if ( defined('HHVM_VERSION') ) {
			$dom->loadHTML(file_get_contents(__DIR__ . '/Fixture/selector_test.html'));
		}
		else {
			$dom->loadHTMLFile(__DIR__ . '/Fixture/selector_test.html');
		}

		$xpath = $this->selector->translateToXPath($how, $using);

		$dom_xpath = new \DOMXPath($dom);
		$node_list = $dom_xpath->query($xpath);

		$this->assertEquals($expected_count, $node_list->length);
	}

	/**
	 * Returns correct locator transformation results.
	 *
	 * @return array
	 */
	public function correctDataProvider()
	{
		return array(
			How::CLASS_NAME . ' (partial)' => array(
				How::CLASS_NAME, 'class-one', 2,
			),
			How::CLASS_NAME . ' (exact)' => array(
				How::CLASS_NAME, 'class-two', 1,
			),
			How::CSS . ' (tag name)' => array(
				How::CSS, 'p', 2,
			),
			How::CSS . ' (id)' => array(
				How::CSS, '#the-paragraph', 1,
			),
			How::CSS . ' (class name)' => array(
				How::CSS, '.class-one', 2,
			),
			How::CSS . ' (mix)' => array(
				How::CSS, '.class-one.class-two', 1,
			),
			How::ID => array(
				How::ID, 'the-paragraph', 1,
			),
			How::NAME => array(
				How::NAME, 'field', 3,
			),
			How::ID_OR_NAME => array(
				How::ID_OR_NAME, 'field', 4,
			),
			How::LINK_TEXT => array(
				How::LINK_TEXT, 'cheese', 1,
			),
			How::PARTIAL_LINK_TEXT => array(
				How::PARTIAL_LINK_TEXT, 'cheese', 2,
			),
			How::TAG_NAME => array(
				How::TAG_NAME, 'a', 3,
			),
			How::XPATH => array(
				How::XPATH, 'descendant-or-self::a[@name]', 1,
			),
			How::LABEL . ' (incomplete label)' => array(
				How::LABEL, 'label', 0,
			),
			How::LABEL . ' (preceding-label)' => array(
				How::LABEL, 'label text 2', 1,
			),
			How::LABEL . ' (following-label)' => array(
				How::LABEL, 'label text 3', 1,
			),
			How::LABEL . ' (label-input-inside)' => array(
				How::LABEL, 'label text 4', 1,
			),
			How::LABEL . ' (label-textarea)' => array(
				How::LABEL, 'label textarea', 1,
			),
			How::LABEL . ' (label-select)' => array(
				How::LABEL, 'label select', 1,
			),
		);
	}

	public function testIncorrect()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\ElementException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\ElementException::TYPE_INCORRECT_SELECTOR);
		$this->expectExceptionMessage('The "using" part the Selenium selector is empty');

		$this->selector->translateToXPath(How::ID, '');
	}

}
