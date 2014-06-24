<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\aik099\QATools\PageObject\Url;


use aik099\QATools\PageObject\Url\Parser;
use Mockery as m;
use tests\aik099\QATools\TestCase;

class ParserTest extends TestCase
{

	/**
	 * @dataProvider constructorAndComponentsDataProvider
	 */
	public function testConstructorAndGetComponents($url, $expected_components)
	{
		$url_parser = new Parser($url);

		$this->assertEquals($expected_components, $url_parser->getComponents());
	}

	public function constructorAndComponentsDataProvider()
	{
		return array(
			array('http://domain.tld', parse_url('http://domain.tld')),
			array('http://domain.tld/path', parse_url('http://domain.tld/path')),
			array('http://domain.tld/path?param=value', parse_url('http://domain.tld/path?param=value')),
			array('http://domain.tld/path?param=value#anchor', parse_url('http://domain.tld/path?param=value#anchor')),
		);
	}

	/**
	 * @expectedException \aik099\QATools\PageObject\Exception\UrlException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\UrlException::TYPE_INVALID_URL
	 */
	public function testConstructorInvalidUrl()
	{
		new Parser('http:///domain.tld');
	}

	public function testGetComponent()
	{
		$url_parser = new Parser('http://domain.tld/path?param=value#anchor');

		$this->assertEquals('http', $url_parser->getComponent('scheme'));
		$this->assertEquals('domain.tld', $url_parser->getComponent('host'));
		$this->assertEquals('/path', $url_parser->getComponent('path'));
		$this->assertEquals('param=value', $url_parser->getComponent('query'));
		$this->assertEquals('anchor', $url_parser->getComponent('fragment'));
	}

	public function testGetComponentDefault()
	{
		$url_parser = new Parser('');

		$this->assertEquals('default_scheme', $url_parser->getComponent('scheme', 'default_scheme'));
		$this->assertEquals('default_host', $url_parser->getComponent('host', 'default_host'));
		$this->assertEquals('default_path', $url_parser->getComponent('path', 'default_path'));
		$this->assertEquals('default_query', $url_parser->getComponent('query', 'default_query'));
		$this->assertEquals('default_fragment', $url_parser->getComponent('fragment', 'default_fragment'));
	}

	public function testSetGetParams()
	{
		$url_parser = new Parser('http://domain.tld/path?param=value#anchor');
		$updated_params = array('param' => 'value', 'new_param' => 'new_value');

		$this->assertEquals(array('param' => 'value'), $url_parser->getParams());

		$url_parser->setParams($updated_params);

		$this->assertEquals($updated_params, $url_parser->getParams());
		$this->assertEquals(http_build_query($updated_params), $url_parser->getComponent('query'));
	}

	/**
	 * @dataProvider mergeDataProvider
	 */
	public function testMerge($first_url, $second_url, $expected_component, $expected_value)
	{
		$url_parser = new Parser($first_url);
		$url_parser->merge(new Parser($second_url));

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
			// Test the priority on merge.
			array('http://domain.tld/path#anchor', 'https://another.tld/path2#anchor2', 'scheme', 'https'),
			array('http://domain.tld/path#anchor', 'https://another.tld/path2#anchor2', 'host', 'another.tld'),
			array('http://domain.tld/path#anchor', 'https://another.tld/path2#anchor2', 'path', '/path2'),
			array('http://domain.tld/path#anchor', 'https://another.tld/path2#anchor2', 'fragment', 'anchor2'),
		);
	}

}
