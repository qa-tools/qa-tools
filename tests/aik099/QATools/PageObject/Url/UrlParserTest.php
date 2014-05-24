<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\PageObject\Url;


use aik099\QATools\PageObject\Url\UrlParser;
use Mockery as m;
use tests\aik099\QATools\TestCase;

class UrlParserTest extends TestCase
{

	/**
	 * @dataProvider constructorAndGetterDataProvider
	 */
	public function testConstructorAndGetter(
		$url,
		$expected_scheme,
		$expected_host,
		$expected_path,
		$expected_query,
		$expected_fragment,
		$expected_params
	)
	{
		$url_parser = new UrlParser($url);

		$this->assertEquals($expected_scheme, $url_parser->getComponent('scheme'));
		$this->assertEquals($expected_host, $url_parser->getComponent('host'));
		$this->assertEquals($expected_path, $url_parser->getComponent('path'));
		$this->assertEquals($expected_query, $url_parser->getComponent('query'));
		$this->assertEquals($expected_fragment, $url_parser->getComponent('fragment'));
		$this->assertEquals($expected_params, $url_parser->getParams());
	}

	public function constructorAndGetterDataProvider()
	{
		return array(
			array(
				'http://domain.tld',
				'http',
				'domain.tld',
				'',
				'',
				'',
				array(),
			),
			array(
				'http://domain.tld/path',
				'http',
				'domain.tld',
				'/path',
				'',
				'',
				array(),
			),
			array(
				'http://domain.tld/path?param=value',
				'http',
				'domain.tld',
				'/path',
				'param=value',
				'',
				array('param' => 'value'),
			),
			array(
				'http://domain.tld/path?param=value#anchor',
				'http',
				'domain.tld',
				'/path',
				'param=value',
				'anchor',
				array('param' => 'value'),
			),
		);
	}

	public function testGetComponentDefault()
	{
		$url_parser = new UrlParser('');

		$this->assertEquals('default_scheme', $url_parser->getComponent('scheme', 'default_scheme'));
		$this->assertEquals('default_host', $url_parser->getComponent('host', 'default_host'));
		$this->assertEquals('default_path', $url_parser->getComponent('path', 'default_path'));
		$this->assertEquals('default_query', $url_parser->getComponent('query', 'default_query'));
		$this->assertEquals('default_fragment', $url_parser->getComponent('fragment', 'default_fragment'));
	}

	/**
	 * @dataProvider mergeDataProvider
	 */
	public function testMerge($first_url, $second_url, $expected_component, $expected_value)
	{
		$url_parser = new UrlParser($first_url);
		$url_parser->merge(new UrlParser($second_url));

		$this->assertEquals($expected_value, $url_parser->getComponent($expected_component));
	}

	public function mergeDataProvider()
	{
		return array(
			array('http://domain.tld#anchor', '/path?param=value', 'scheme', 'http'),
			array('http://domain.tld#anchor', '/path?param=value', 'host', 'domain.tld'),
			array('http://domain.tld#anchor', '/path?param=value', 'path', '/path'),
			array('http://domain.tld#anchor', '/path?param=value', 'query', 'param=value'),
			array('http://domain.tld#anchor', '/path?param=value', 'fragment', 'anchor'),
		);
	}

}
