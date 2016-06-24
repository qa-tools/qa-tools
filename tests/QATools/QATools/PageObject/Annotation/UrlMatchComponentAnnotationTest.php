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


use QATools\QATools\PageObject\Annotation\MatchUrlComponentAnnotation;

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
		$expected_secure,
		$expected_host,
		$expected_port,
		$expected_user,
		$expected_pass
	) {
		$annotation = new MatchUrlComponentAnnotation();
		$annotation->initAnnotation($annotation_params);

		$this->assertEquals($expected_path, $annotation->path);
		$this->assertEquals($expected_params, $annotation->params);
		$this->assertEquals($expected_anchor, $annotation->anchor);
		$this->assertEquals($expected_secure, $annotation->secure);
		$this->assertEquals($expected_host, $annotation->host);
		$this->assertEquals($expected_port, $annotation->port);
		$this->assertEquals($expected_user, $annotation->user);
		$this->assertEquals($expected_pass, $annotation->pass);

		$this->assertTrue($annotation->isValid());
	}

	public function initAnnotationDataProvider()
	{
		return array(
			'all at once' => array(
				array('path', array('param'), 'anchor', true, 'host', 80, 'username', 'password'),
				'path',
				array('param'),
				'anchor',
				true,
				'host',
				80,
				'username',
				'password',
			),
			'only secure = yes' => array(
				array('secure' => true),
				null,
				null,
				null,
				true,
				null,
				null,
				null,
				null,
			),
			'only secure = no' => array(
				array('secure' => false),
				null,
				null,
				null,
				false,
				null,
				null,
				null,
				null,
			),
			'only path' => array(
				array('path' => '/test'),
				'/test',
				null,
				null,
				null,
				null,
				null,
				null,
				null,
			),
			'only params' => array(
				array('params' => array('param' => 'value')),
				null,
				array('param' => 'value'),
				null,
				null,
				null,
				null,
				null,
				null,
			),
			'only anchor' => array(
				array('anchor' => 'test'),
				null,
				null,
				'test',
				null,
				null,
				null,
				null,
				null,
			),
			'only host' => array(
				array('host' => 'host'),
				null,
				null,
				null,
				null,
				'host',
				null,
				null,
				null,
			),
			'only port' => array(
				array('port' => 80),
				null,
				null,
				null,
				null,
				null,
				80,
				null,
				null,
			),
			'only username' => array(
				array('user' => 'username'),
				null,
				null,
				null,
				null,
				null,
				null,
				'username',
				null,
			),
			'only password' => array(
				array('pass' => 'password'),
				null,
				null,
				null,
				null,
				null,
				null,
				null,
				'password',
			),
		);
	}

	public function testInvalidAnnotation()
	{
		$annotation = new MatchUrlComponentAnnotation();
		$this->assertFalse($annotation->isValid());
	}

}
