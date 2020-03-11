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
use QATools\QATools\PageObject\Annotation\MatchUrlRegexpAnnotation;

class UrlMatchRegexpAnnotationTest extends TestCase
{

	use MockeryPHPUnitIntegration;

	/**
	 * @dataProvider initAnnotationDataProvider
	 */
	public function testInitAnnotation(array $annotation_params, $expected_regexp)
	{
		$annotation = new MatchUrlRegexpAnnotation();
		$annotation->initAnnotation($annotation_params);

		$this->assertEquals($expected_regexp, $annotation->regexp);

		$this->assertTrue($annotation->isValid());
	}

	public function initAnnotationDataProvider()
	{
		return array(
			'by order' => array(
				array('regexp'),
				'regexp',
			),
			'by name' => array(
				array('regexp' => 'regexp'),
				'regexp',
			),
		);
	}

	public function testInvalidAnnotation()
	{
		$annotation = new MatchUrlRegexpAnnotation();
		$this->assertFalse($annotation->isValid());
	}

}
