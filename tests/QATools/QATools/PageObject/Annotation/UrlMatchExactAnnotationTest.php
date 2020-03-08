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
use QATools\QATools\PageObject\Annotation\MatchUrlExactAnnotation;

class UrlMatchExactAnnotationTest extends TestCase
{

	use MockeryPHPUnitIntegration;

	/**
	 * @dataProvider initAnnotationDataProvider
	 */
	public function testInitAnnotation(array $annotation_params, $expected_url)
	{
		$annotation = new MatchUrlExactAnnotation();
		$annotation->initAnnotation($annotation_params);

		$this->assertEquals($expected_url, $annotation->url);

		$this->assertTrue($annotation->isValid());
	}

	public function initAnnotationDataProvider()
	{
		return array(
			'by order' => array(
				array('value'),
				'value',
			),
			'by name' => array(
				array('url' => 'value'),
				'value',
			),
		);
	}

	public function testInvalidAnnotation()
	{
		$annotation = new MatchUrlExactAnnotation();
		$this->assertFalse($annotation->isValid());
	}

}
