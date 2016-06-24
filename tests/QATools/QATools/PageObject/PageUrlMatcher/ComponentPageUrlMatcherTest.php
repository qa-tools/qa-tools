<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\PageUrlMatcher;


use Mockery as m;
use QATools\QATools\PageObject\Annotation\MatchUrlComponentAnnotation;

class ComponentPageUrlMatcherTest extends AbstractPageUrlMatcherTestCase
{

	protected function setUp()
	{
		$this->className = '\\QATools\\QATools\\PageObject\\PageUrlMatcher\\ComponentPageUrlMatcher';

		parent::setUp();
	}

	public function testPriority()
	{
		$this->assertEquals(1, $this->pageUrlMatcher->getPriority());
	}

	public function testGetAnnotationName()
	{
		$this->assertEquals('match-url-component', $this->pageUrlMatcher->getAnnotationName());
	}

	public function testGetAnnotationClass()
	{
		$this->assertEquals(
			'\\QATools\\QATools\\PageObject\\Annotation\\MatchUrlComponentAnnotation',
			$this->pageUrlMatcher->getAnnotationClass()
		);
	}

	/**
	 * Creates annotation instance, that matcher will use.
	 *
	 * @param array $parameters Parameters.
	 *
	 * @return MatchUrlComponentAnnotation
	 */
	public function createAnnotation(array $parameters)
	{
		$annotation = new MatchUrlComponentAnnotation();
		$annotation->initAnnotation($parameters);

		return $annotation;
	}

	public function matchesDataProvider()
	{
		return array(
			'no annotations' => array(
				array(),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			'path matched' => array(
				array(array('path' => '/relative')),
				'http://domain.tld/relative?param=value#fragment',
				true,
			),
			'path not matched' => array(
				array(array('path' => '/not_relative')),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			'param matched' => array(
				array(array('params' => array('param' => 'value'))),
				'http://domain.tld/relative?param=value#fragment',
				true,
			),
			'param not matched' => array(
				array(array('params' => array('param' => 'not_matching'))),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			'two params matched (same order)' => array(
				array(array('params' => array('param1' => 'value1', 'param2' => 'value2'))),
				'http://domain.tld/relative?param1=value1&param2=value2#fragment',
				true,
			),
			'two params matched (different order)' => array(
				array(array('params' => array('param2' => 'value2', 'param1' => 'value1'))),
				'http://domain.tld/relative?param1=value1&param2=value2#fragment',
				true,
			),
			'insecure matched' => array(
				array(array('secure' => false)),
				'http://domain.tld/relative?param=value#fragment',
				true,
			),
			'secure not matched' => array(
				array(array('secure' => true)),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			'anchor matched' => array(
				array(array('anchor' => 'fragment')),
				'http://domain.tld/relative?param=value#fragment',
				true,
			),
			'anchor not matched' => array(
				array(array('anchor' => 'wrong')),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			'hostname matched' => array(
				array(array('host' => 'domain.tld')),
				'http://domain.tld/relative?param=value#fragment',
				true,
			),
			'hostname not matched' => array(
				array(array('host' => 'wrong.tld')),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			'port matched' => array(
				array(array('port' => 80)),
				'http://domain.tld:80/relative?param=value#fragment',
				true,
			),
			'port not matched' => array(
				array(array('port' => 80)),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			'username matched' => array(
				array(array('user' => 'username')),
				'http://username@domain.tld/relative?param=value#fragment',
				true,
			),
			'username not matched' => array(
				array(array('user' => 'username')),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			'password matched' => array(
				array(array('pass' => 'password')),
				'http://username:password@domain.tld/relative?param=value#fragment',
				true,
			),
			'username & password matched' => array(
				array(array('user' => 'username', 'pass' => 'password')),
				'http://username:password@domain.tld/relative?param=value#fragment',
				true,
			),
			'password not matched' => array(
				array(array('pass' => 'password')),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			'hostname & path matched' => array(
				array(array('host' => 'wrong.tld'), array('path' => '/relative')),
				'http://domain.tld/relative?param=value#fragment',
				true,
			),
		);
	}

}
