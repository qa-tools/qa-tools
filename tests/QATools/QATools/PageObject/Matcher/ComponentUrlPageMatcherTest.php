<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Matcher;


use Mockery as m;
use QATools\QATools\PageObject\Annotation\UrlMatchComponentAnnotation;
use QATools\QATools\PageObject\Matcher\ComponentUrlPageMatcher;
use tests\QATools\QATools\TestCase;

class ComponentUrlPageMatcherTest extends TestCase
{
	/**
	 * @dataProvider matchesDataProvider
	 */
	public function testMatches($annotations, $url, $expected_matches)
	{
		$parsed_annotations = array();

		foreach ($annotations as $annotation) {
			$parsed_annotation = new UrlMatchComponentAnnotation();
			$parsed_annotation->initAnnotation($annotation);
			$parsed_annotations[] = $parsed_annotation;
		}

		$matcher = new ComponentUrlPageMatcher();

		$this->assertEquals($expected_matches, $matcher->matches($url, $parsed_annotations));
	}

	public function matchesDataProvider()
	{
		return array(
			array(
				array(),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			array(
				array(array('path' => '/relative')),
				'http://domain.tld/relative?param=value#fragment',
				true,
			),
			array(
				array(array('path' => '/not_relative')),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			array(
				array(array('params' => array('param' => 'value'))),
				'http://domain.tld/relative?param=value#fragment',
				true,
			),
			array(
				array(array('params' => array('param' => 'not_matching'))),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			array(
				array(array('params' => array('param1' => 'value1', 'param2' => 'value2'))),
				'http://domain.tld/relative?param1=value1&param2=value2#fragment',
				true,
			),
			array(
				array(array('params' => array('param2' => 'value2', 'param1' => 'value1'))),
				'http://domain.tld/relative?param1=value1&param2=value2#fragment',
				true,
			),
			array(
				array(array('secure' => false)),
				'http://domain.tld/relative?param=value#fragment',
				true,
			),
			array(
				array(array('secure' => true)),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			array(
				array(array('anchor' => 'fragment')),
				'http://domain.tld/relative?param=value#fragment',
				true,
			),
			array(
				array(array('anchor' => 'wrong')),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			array(
				array(array('host' => 'domain.tld')),
				'http://domain.tld/relative?param=value#fragment',
				true,
			),
			array(
				array(array('host' => 'wrong.tld')),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			array(
				array(array('port' => 80)),
				'http://domain.tld:80/relative?param=value#fragment',
				true,
			),
			array(
				array(array('port' => 80)),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			array(
				array(array('user' => 'username')),
				'http://username@domain.tld/relative?param=value#fragment',
				true,
			),
			array(
				array(array('user' => 'username')),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			array(
				array(array('pass' => 'password')),
				'http://username:password@domain.tld/relative?param=value#fragment',
				true,
			),
			array(
				array(array('user' => 'username', 'pass' => 'password')),
				'http://username:password@domain.tld/relative?param=value#fragment',
				true,
			),
			array(
				array(array('pass' => 'password')),
				'http://domain.tld/relative?param=value#fragment',
				false,
			),
			array(
				array(array('host' => 'wrong.tld'), array('path' => '/relative')),
				'http://domain.tld/relative?param=value#fragment',
				true,
			),
		);
	}

}
