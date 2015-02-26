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


use QATools\QATools\PageObject\Annotation\UrlMatchComponentAnnotation;

class UrlMatchComponentAnnotationTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @dataProvider initAnnotationDataProvider
	 */
	public function testInitAnnotation(
		array $annotation_params,
		$expected_path,
		$expected_params,
		$expected_anchor,
		$expected_secure
	) {
		$annotation = new UrlMatchComponentAnnotation();
		$annotation->initAnnotation($annotation_params);

		$this->assertEquals($expected_path, $annotation->path);
		$this->assertEquals($expected_params, $annotation->params);
		$this->assertEquals($expected_anchor, $annotation->anchor);
		$this->assertEquals($expected_secure, $annotation->secure);
	}

	public function initAnnotationDataProvider()
	{
		return array(
			array(
				array('path', array('param'), 'anchor', true),
				'path',
				array('param'),
				'anchor',
				true,
			),
			array(
				array('secure' => true),
				null,
				null,
				null,
				true,
			),
			array(
				array('secure' => false),
				null,
				null,
				null,
				false,
			),
			array(
				array('path' => '/test'),
				'/test',
				null,
				null,
				null,
			),
			array(
				array('params' => array('param' => 'value')),
				null,
				array('param' => 'value'),
				null,
				null,
			),
			array(
				array('anchor' => 'test'),
				null,
				null,
				'test',
				null,
			),
		);
	}

}
