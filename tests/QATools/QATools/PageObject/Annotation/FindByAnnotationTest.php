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

class FindByAnnotationTest extends TestCase
{

	use MockeryPHPUnitIntegration;

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

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\AnnotationException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\AnnotationException::TYPE_INCORRECT_USAGE
	 * @expectedExceptionMessage FindBy annotation requires one of 'className', 'css', 'id', 'linkText', 'name', 'partialLinkText', 'tagName', 'xpath', 'label' or both 'how' and 'using' parameters specified
	 */
	public function testUnknownDirectSelector()
	{
		$annotation = new FindByAnnotation();
		$annotation->getSelector();
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\AnnotationException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\AnnotationException::TYPE_INCORRECT_USAGE
	 * @expectedExceptionMessage FindBy annotation expects 'how' to be one of \QATools\QATools\PageObject\How class constants
	 */
	public function testUnknownHowSelector()
	{
		$annotation = new FindByAnnotation();
		$annotation->how = 'wrong';
		$annotation->using = 'test';

		$annotation->getSelector();
	}

}
