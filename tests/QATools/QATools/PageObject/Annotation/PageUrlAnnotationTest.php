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
use QATools\QATools\PageObject\Annotation\PageUrlAnnotation;

class PageUrlAnnotationTest extends TestCase
{

	use MockeryPHPUnitIntegration;

	/**
	 * @dataProvider initAnnotationDataProvider
	 */
	public function testInitAnnotation(
		array $annotation_params,
		$expected_url,
		array $expected_params,
		$expected_secure
	) {
		$annotation = new PageUrlAnnotation();
		$annotation->initAnnotation($annotation_params);

		$this->assertEquals($expected_url, $annotation->url);
		$this->assertEquals($expected_params, $annotation->params);
		$this->assertEquals($expected_secure, $annotation->secure);
	}

	public function initAnnotationDataProvider()
	{
		return array(
			array(
				array('/test'),
				'/test',
				array(),
				null,
			),
			array(
				array('/test', array('param' => 'value')),
				'/test',
				array('param' => 'value'),
				null,
			),
			array(
				array('/test', array('param' => 'value'), false),
				'/test',
				array('param' => 'value'),
				null,
			),
			array(
				array('url' => '/test'),
				'/test',
				array(),
				null,
			),
			array(
				array('url' => '/test', 'params' => array('param' => 'value')),
				'/test',
				array('param' => 'value'),
				null,
			),
			array(
				array('url' => '/test', 'params' => array('param' => 'value'), 'secure' => true),
				'/test',
				array('param' => 'value'),
				true,
			),
		);
	}

}
