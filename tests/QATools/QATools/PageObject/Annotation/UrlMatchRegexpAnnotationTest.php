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


use QATools\QATools\PageObject\Annotation\UrlMatchRegexpAnnotation;

class UrlMatchRegexpAnnotationTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @dataProvider initAnnotationDataProvider
	 */
	public function testInitAnnotation(array $annotation_params, $expected_regexp)
	{
		$annotation = new UrlMatchRegexpAnnotation();
		$annotation->initAnnotation($annotation_params);

		$this->assertEquals($expected_regexp, $annotation->regexp);
	}

	public function initAnnotationDataProvider()
	{
		return array(
			array(
				array('regexp'),
				'regexp',
			),
			array(
				array('regexp' => 'regexp'),
				'regexp',
			),
		);
	}

}
