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


use QATools\QATools\PageObject\Url\Builder;
use Mockery as m;
use tests\QATools\QATools\TestCase;

class BuilderTest extends TestCase
{

	public function testConstructor()
	{
		$normalized_components = array(
			'scheme' => 'http',
			'host' => 'domain.tld',
			'path' => '/path',
			'query' => 'param=value',
			'fragment' => 'anchor',
			'port' => '8080',
		);

		$expected_params = array();

		parse_str($normalized_components['query'], $expected_params);

		$builder = new Builder($normalized_components);

		$this->assertEquals($normalized_components['scheme'], $builder->getProtocol());
		$this->assertEquals($normalized_components['host'], $builder->getHost());
		$this->assertEquals($normalized_components['path'], $builder->getPath());
		$this->assertEquals($expected_params, $builder->getParams());
		$this->assertEquals($normalized_components['fragment'], $builder->getAnchor());
		$this->assertEquals($normalized_components['port'], $builder->getPort());
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\UrlException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\UrlException::TYPE_INVALID_URL
	 * @expectedExceptionMessage No base url specified
	 */
	public function testConstructorMissingProtocol()
	{
		$normalized_components = array(
			'scheme' => '',
			'host' => 'domain.tld',
			'path' => '/path',
			'query' => 'param=value',
			'fragment' => 'anchor',
		);

		new Builder($normalized_components);
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\UrlException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\UrlException::TYPE_INVALID_URL
	 * @expectedExceptionMessage No base url specified
	 */
	public function testConstructorMissingHost()
	{
		$normalized_components = array(
			'scheme' => 'http',
			'host' => '',
			'path' => '/path',
			'query' => 'param=value',
			'fragment' => 'anchor',
		);

		new Builder($normalized_components);
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\UrlException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\UrlException::TYPE_INVALID_URL
	 * @expectedExceptionMessage No base url specified
	 */
	public function testConstructorEmptyPath()
	{
		$normalized_components = array(
			'scheme' => 'http',
			'host' => 'domain.tld',
			'path' => '',
			'query' => 'param=value',
			'fragment' => 'anchor',
		);

		new Builder($normalized_components);
	}

	/**
	 * @dataProvider buildDataProvider
	 */
	public function testBuild($normalized_components, array $params, $expected_url)
	{
		$url_builder = new Builder($normalized_components);

		$actual_url = $url_builder->build($params);

		$this->assertSame($actual_url, $expected_url);
	}

	public function buildDataProvider()
	{
		return array(
			array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path',
				),
				array(),
				'http://domain.tld/path',
			),
			array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path',
					'query' => 'param=value',
				),
				array(),
				'http://domain.tld/path?param=value',
			),
			array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path',
					'query' => 'param=value',
					'fragment' => 'anchor',
				),
				array(),
				'http://domain.tld/path?param=value#anchor',
			),
			array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path',
					'port' => 8080,
				),
				array(),
				'http://domain.tld:8080/path',
			),
			array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path',
					'port' => 80,
				),
				array(),
				'http://domain.tld/path',
			),
			array(
				array(
					'scheme' => 'https',
					'host' => 'domain.tld',
					'path' => '/path',
					'port' => 443,
				),
				array(),
				'https://domain.tld/path',
			),
			'one parameter unmasked' => array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path/{replace_me}',
					'port' => 80,
				),
				array('replace_me' => 'i-was-replaced'),
				'http://domain.tld/path/i-was-replaced',
			),
			'unmasked parameter and query string' => array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path/{replace_me}',
					'port' => 80,
				),
				array(
					'replace_me' => 'i-was-replaced',
					'more_params' => 'some-query-string-value',
				),
				'http://domain.tld/path/i-was-replaced?more_params=some-query-string-value',
			),
			'two parameters unmasked' => array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path/{replace_me}/{2nd_replace}',
					'port' => 80,
				),
				array(
					'replace_me' => 'i-was-replaced',
					'2nd_replace' => 'it-works',
				),
				'http://domain.tld/path/i-was-replaced/it-works',
			),
			'broken mask not unmasked' => array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path/{i_am_broken/{replace_me}',
					'port' => 80,
				),
				array('replace_me' => 'i-was-replaced'),
				'http://domain.tld/path/{i_am_broken/i-was-replaced',
			),
			'duplicate mask unmasked' => array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path/{replace_me}/{replace_me}',
					'port' => 80,
				),
				array('replace_me' => 'i-was-replaced'),
				'http://domain.tld/path/i-was-replaced/i-was-replaced',
			),
			'optional parameter unmasking' => array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path/{replace_me}/{optional}',
					'port' => 80,
				),
				array('replace_me' => 'i-was-replaced', 'optional' => null),
				'http://domain.tld/path/i-was-replaced/',
			),
			'parameter url encoding during unmasking' => array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path/{replace_me}',
					'port' => 80,
				),
				array('replace_me' => 'i have wärd+ ßymbol$'),
				'http://domain.tld/path/i%20have%20w%C3%A4rd%2B%20%C3%9Fymbol%24',
			),
		);
	}

	/**
	 * @dataProvider buildExceptionDataProvider
	 * @expectedException \QATools\QATools\PageObject\Exception\MissingParametersException
	 * @expectedExceptionMessage No parameters for "i_do_not_exist" masks given.
	 */
	public function testBuildParamException($normalized_components, array $params)
	{
		$url_builder = new Builder($normalized_components);
		$url_builder->build($params);
	}

	public function buildExceptionDataProvider()
	{
		return array(
			array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path/{i_do_not_exist}/{replace_me}',
					'port' => 80,
				),
				array('replace_me' => 'i-was-replaced'),
			),
			array(
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path/{i_do_not_exist}/{replace_me}',
					'port' => 80,
				),
				array('replace_me' => 'i-was-replaced', 'query' => 'string'),
			),
		);
	}

}
