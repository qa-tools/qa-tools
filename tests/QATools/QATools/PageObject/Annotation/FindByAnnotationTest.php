<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Annotation;


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use QATools\QATools\PageObject\Annotation\FindByAnnotation;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectException;

class FindByAnnotationTest extends TestCase
{

	use MockeryPHPUnitIntegration, ExpectException;

	/**
	 * @dataProvider selectorDataProvider
	 */
	public function testDirectSelector($property_name)
	{
		$annotation = new FindByAnnotation();
		$annotation->$property_name = 'test';

		$this->assertEquals(array($property_name => 'test'), $annotation->getSelector());
	}

	/**
	 * @dataProvider selectorDataProvider
	 */
	public function testHowAndUsingSelector($property_name)
	{
		$annotation = new FindByAnnotation();
		$annotation->how = $property_name;
		$annotation->using = 'test';

		$this->assertEquals(array($property_name => 'test'), $annotation->getSelector());
	}

	/**
	 * Provides test data for direct property assignment test.
	 *
	 * @return array
	 */
	public function selectorDataProvider()
	{
		return array(
			array('className'),
			array('css'),
			array('id'),
			array('linkText'),
			array('name'),
			array('partialLinkText'),
			array('tagName'),
			array('xpath'),
			array('label'),
		);
	}

	public function testUnknownDirectSelector()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\AnnotationException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\AnnotationException::TYPE_INCORRECT_USAGE);
		$this->expectExceptionMessage("FindBy annotation requires one of 'className', 'css', 'id', 'linkText', 'name', 'partialLinkText', 'tagName', 'xpath', 'label' or both 'how' and 'using' parameters specified");

		$annotation = new FindByAnnotation();
		$annotation->getSelector();
	}

	public function testUnknownHowSelector()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\AnnotationException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\AnnotationException::TYPE_INCORRECT_USAGE);
		$this->expectExceptionMessage("FindBy annotation expects 'how' to be one of \QATools\QATools\PageObject\How class constants");

		$annotation = new FindByAnnotation();
		$annotation->how = 'wrong';
		$annotation->using = 'test';

		$annotation->getSelector();
	}

}
