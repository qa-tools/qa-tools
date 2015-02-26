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


use QATools\QATools\PageObject\Annotation\UrlMatchFullAnnotation;

class UrlMatchFullAnnotationTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @dataProvider initAnnotationDataProvider
	 */
	public function testInitAnnotation(array $annotation_params, $expected_url)
	{
		$annotation = new UrlMatchFullAnnotation();
		$annotation->initAnnotation($annotation_params);

		$this->assertEquals($expected_url, $annotation->url);
	}

	public function initAnnotationDataProvider()
	{
		return array(
			array(
				array('value'),
				'value',
			),
			array(
				array('url' => 'value'),
				'value',
			),
		);
	}

}
