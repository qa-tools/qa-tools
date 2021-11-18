<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Url;


use QATools\QATools\PageObject\Url\Parser;
use Mockery as m;
use tests\QATools\QATools\TestCase;

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
			array('http://domain.tld:8080', parse_url('http://domain.tld:8080')),
			array('http://domain.tld/path', parse_url('http://domain.tld/path')),
			array('http://domain.tld/path?param=value', parse_url('http://domain.tld/path?param=value')),
			array('http://domain.tld/path?param=value#anchor', parse_url('http://domain.tld/path?param=value#anchor')),
		);
	}

	public function testConstructorInvalidUrl()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\UrlException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\UrlException::TYPE_INVALID_URL);
		$this->expectExceptionMessage('http:///domain.tld is not a valid url');

		new Parser('http:///domain.tld');
	}

	public function testGetComponent()
	{
		$url_parser = new Parser('http://domain.tld:8080/path?param=value#anchor');

		$this->assertEquals('http', $url_parser->getComponent('scheme'));
		$this->assertEquals('domain.tld', $url_parser->getComponent('host'));
		$this->assertEquals('/path', $url_parser->getComponent('path'));
		$this->assertEquals('param=value', $url_parser->getComponent('query'));
		$this->assertEquals('anchor', $url_parser->getComponent('fragment'));
		$this->assertEquals(8080, $url_parser->getComponent('port'));
	}

	public function testGetComponentDefault()
	{
		$url_parser = new Parser('');

		$this->assertEquals('default_scheme', $url_parser->getComponent('scheme', 'default_scheme'));
		$this->assertEquals('default_host', $url_parser->getComponent('host', 'default_host'));
		$this->assertEquals('default_path', $url_parser->getComponent('path', 'default_path'));
		$this->assertEquals('default_query', $url_parser->getComponent('query', 'default_query'));
		$this->assertEquals('default_fragment', $url_parser->getComponent('fragment', 'default_fragment'));
		$this->assertEquals(8080, $url_parser->getComponent('port', 8080));
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
	public function testMerge($first_url, $second_url, array $expected_components)
	{
		$url_parser = new Parser($first_url);
		$url_parser->merge(new Parser($second_url));

		foreach ( $expected_components as $expected_component => $expected_value ) {
			$this->assertEquals(
				$expected_value,
				$url_parser->getComponent($expected_component),
				'The "' . $expected_component . '" has expected value.'
			);
		}
	}

	/**
	 * @see NormalizerTest::normalizeDataProvider
	 */
	public function mergeDataProvider()
	{
		return array(
			'absolute and absolute url' => array(
				'http://user1:pass1@domain1:1111/folder1/?param1=value1#anchor1',
				'https://user2:pass2@domain2:2222/folder2/?param2=value2#anchor2',
				array(
					'scheme' => 'https',
					'host' => 'domain2',
					'port' => 2222,
					'user' => 'user2',
					'pass' => 'pass2',
					'path' => '/folder1/folder2/',
					'query' => 'param1=value1&param2=value2',
					'fragment' => 'anchor2',
				),
			),
			'absolute and relative url' => array(
				'http://user1:pass1@domain1:1111/folder1/?param1=value1#anchor1',
				'/folder2/?param2=value2#anchor2',
				array(
					'scheme' => 'http',
					'host' => 'domain1',
					'port' => 1111,
					'user' => 'user1',
					'pass' => 'pass1',
					'path' => '/folder1/folder2/',
					'query' => 'param1=value1&param2=value2',
					'fragment' => 'anchor2',
				),
			),
			'relative and absolute url' => array(
				'/folder1/?param1=value1#anchor1',
				'https://user2:pass2@domain2:2222/folder2/?param2=value2#anchor2',
				array(
					'scheme' => 'https',
					'host' => 'domain2',
					'port' => 2222,
					'user' => 'user2',
					'pass' => 'pass2',
					'path' => '/folder1/folder2/',
					'query' => 'param1=value1&param2=value2',
					'fragment' => 'anchor2',
				),
			),
			'domain replacement' => array(
				'http://user1:pass1@domain1:1111/folder1/?param1=value1#anchor1',
				'https://domain2/folder2/?param2=value2#anchor2',
				array(
					'scheme' => 'https',
					'host' => 'domain2',
					'port' => 1111,
					'user' => 'user1',
					'pass' => 'pass1',
					'path' => '/folder1/folder2/',
					'query' => 'param1=value1&param2=value2',
					'fragment' => 'anchor2',
				),
			),
			'adding user, pass, port' => array(
				'http://domain1/folder1/?param1=value1#anchor1',
				'https://user2:pass2@domain1:2222/folder2/?param2=value2#anchor2',
				array(
					'scheme' => 'https',
					'host' => 'domain1',
					'port' => 2222,
					'user' => 'user2',
					'pass' => 'pass2',
					'path' => '/folder1/folder2/',
					'query' => 'param1=value1&param2=value2',
					'fragment' => 'anchor2',
				),
			),
			'relative and relative url' => array(
				'/folder1/?param1=value1#anchor1',
				'/folder2/?param2=value2#anchor2',
				array(
					'scheme' => '',
					'host' => '',
					'port' => '',
					'user' => '',
					'pass' => '',
					'path' => '/folder1/folder2/',
					'query' => 'param1=value1&param2=value2',
					'fragment' => 'anchor2',
				),
			),
			'query string param replace' => array(
				'http://user1:pass1@domain1:1111/folder1/?param1=value1#anchor1',
				'https://user2:pass2@domain2:2222/folder2/?param2=value2&param1=value3#anchor2',
				array(
					'scheme' => 'https',
					'host' => 'domain2',
					'port' => 2222,
					'user' => 'user2',
					'pass' => 'pass2',
					'path' => '/folder1/folder2/',
					'query' => 'param1=value3&param2=value2',
					'fragment' => 'anchor2',
				),
			),
			'nested query string param replace' => array(
				'http://user1:pass1@domain1:1111/folder1/?param1[key1]=value1&param1[key2]=value2#anchor1',
				'https://user2:pass2@domain2:2222/folder2/?param2=value2&param1[key1]=value3#anchor2',
				array(
					'scheme' => 'https',
					'host' => 'domain2',
					'port' => 2222,
					'user' => 'user2',
					'pass' => 'pass2',
					'path' => '/folder1/folder2/',
					'query' => 'param1%5Bkey1%5D=value3&param1%5Bkey2%5D=value2&param2=value2',
					'fragment' => 'anchor2',
				),
			),
		);
	}

}
