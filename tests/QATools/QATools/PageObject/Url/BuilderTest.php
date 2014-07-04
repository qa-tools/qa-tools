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
		);

		$expected_params = array();

		parse_str($normalized_components['query'], $expected_params);

		$builder = new Builder($normalized_components);

		$this->assertEquals($normalized_components['scheme'], $builder->getProtocol());
		$this->assertEquals($normalized_components['host'], $builder->getHost());
		$this->assertEquals($normalized_components['path'], $builder->getPath());
		$this->assertEquals($expected_params, $builder->getParams());
		$this->assertEquals($normalized_components['fragment'], $builder->getAnchor());
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\UrlException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\UrlException::TYPE_INVALID_URL
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
		);
	}

}
