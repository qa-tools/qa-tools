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


use aik099\QATools\PageObject\Url\UrlBuilder;
use Mockery as m;
use tests\aik099\QATools\TestCase;

class UrlBuilderTest extends TestCase
{

	/**
	 * @dataProvider constructorDataProvider
	 */
	public function testConstructor(
		$url,
		array $params,
		$base_url,
		$expected_protocol,
		$expected_host,
		$expected_path,
		array $expected_params,
		$expected_anchor
	)
	{
		$url_builder = new UrlBuilder($url, $params, $base_url);

		$this->assertEquals($url_builder->getProtocol(), $expected_protocol);
		$this->assertEquals($url_builder->getHost(), $expected_host);
		$this->assertEquals($url_builder->getPath(), $expected_path);
		$this->assertEquals($url_builder->getParams(), $expected_params);
		$this->assertEquals($url_builder->getAnchor(), $expected_anchor);
	}

	public function constructorDataProvider()
	{
		return array(
			array(
				'/path',
				array(),
				'http://domain.tld',
				'http',
				'domain.tld',
				'/path',
				array(),
				'',
			),
			array(
				'/path',
				array('param' => 'value'),
				'http://domain.tld',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value'),
				'',
			),
			array(
				'/path?param=value',
				array(),
				'http://domain.tld',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value'),
				'',
			),
			array(
				'/path?param1=value1',
				array('param2' => 'value2'),
				'http://domain.tld',
				'http',
				'domain.tld',
				'/path',
				array('param1' => 'value1', 'param2' => 'value2'),
				'',
			),
			array(
				'/path?param=value1',
				array('param' => 'value2'),
				'http://domain.tld',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value2'),
				'',
			),
			array(
				'/path#anchor',
				array(),
				'http://domain.tld',
				'http',
				'domain.tld',
				'/path',
				array(),
				'anchor',
				'anchor',
			),
			array(
				'/path#anchor',
				array('param' => 'value'),
				'http://domain.tld',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value'),
				'anchor',
			),
			array(
				'/path?param=value#anchor',
				array(),
				'http://domain.tld',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value'),
				'anchor',
			),
			array(
				'/path?param1=value1#anchor',
				array('param2' => 'value2'),
				'http://domain.tld',
				'http',
				'domain.tld',
				'/path',
				array('param1' => 'value1', 'param2' => 'value2'),
				'anchor',
			),
			array(
				'/path?param=value1#anchor',
				array('param' => 'value2'),
				'http://domain.tld',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value2'),
				'anchor',
			),
			// Absolute url.
			array(
				'http://domain.tld/path',
				array(),
				'',
				'http',
				'domain.tld',
				'/path',
				array(),
				'',
			),
			array(
				'http://domain.tld/path',
				array('param' => 'value'),
				'',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value'),
				'',
			),
			array(
				'http://domain.tld/path?param=value',
				array(),
				'',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value'),
				'',
			),
			array(
				'http://domain.tld/path?param1=value1',
				array('param2' => 'value2'),
				'',
				'http',
				'domain.tld',
				'/path',
				array('param1' => 'value1', 'param2' => 'value2'),
				'',
			),
			array(
				'http://domain.tld/path?param=value1',
				array('param' => 'value2'),
				'',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value2'),
				'',
			),
			array(
				'http://domain.tld/path#anchor',
				array(),
				'',
				'http',
				'domain.tld',
				'/path',
				array(),
				'anchor',
				'anchor',
			),
			array(
				'http://domain.tld/path#anchor',
				array('param' => 'value'),
				'',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value'),
				'anchor',
			),
			array(
				'http://domain.tld/path?param=value#anchor',
				array(),
				'',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value'),
				'anchor',
			),
			array(
				'http://domain.tld/path?param1=value1#anchor',
				array('param2' => 'value2'),
				'',
				'http',
				'domain.tld',
				'/path',
				array('param1' => 'value1', 'param2' => 'value2'),
				'anchor',
			),
			array(
				'http://domain.tld/path?param=value1#anchor',
				array('param' => 'value2'),
				'',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value2'),
				'anchor',
			),
			// Absolute url and base url.
			array(
				'http://domain.tld/path',
				array(),
				'http://base.tld',
				'http',
				'domain.tld',
				'/path',
				array(),
				'',
			),
			array(
				'http://domain.tld/path',
				array('param' => 'value'),
				'http://base.tld',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value'),
				'',
			),
			array(
				'http://domain.tld/path?param=value',
				array(),
				'http://base.tld',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value'),
				'',
			),
			array(
				'http://domain.tld/path?param1=value1',
				array('param2' => 'value2'),
				'http://base.tld',
				'http',
				'domain.tld',
				'/path',
				array('param1' => 'value1', 'param2' => 'value2'),
				'',
			),
			array(
				'http://domain.tld/path?param=value1',
				array('param' => 'value2'),
				'http://base.tld',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value2'),
				'',
			),
			array(
				'http://domain.tld/path#anchor',
				array(),
				'http://base.tld',
				'http',
				'domain.tld',
				'/path',
				array(),
				'anchor',
				'anchor',
			),
			array(
				'http://domain.tld/path#anchor',
				array('param' => 'value'),
				'http://base.tld',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value'),
				'anchor',
			),
			array(
				'http://domain.tld/path?param=value#anchor',
				array(),
				'http://base.tld',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value'),
				'anchor',
			),
			array(
				'http://domain.tld/path?param1=value1#anchor',
				array('param2' => 'value2'),
				'http://base.tld',
				'http',
				'domain.tld',
				'/path',
				array('param1' => 'value1', 'param2' => 'value2'),
				'anchor',
			),
			array(
				'http://domain.tld/path?param=value1#anchor',
				array('param' => 'value2'),
				'http://base.tld',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value2'),
				'anchor',
			),
		);
	}

	/**
	 * @expectedException \aik099\QATools\PageObject\Exception\UrlBuilderException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\UrlBuilderException::TYPE_EMPTY_PATH
	 */
	public function testConstructorEmptyPath()
	{
		new UrlBuilder('http://domain.tld');
	}

	/**
	 * @expectedException \aik099\QATools\PageObject\Exception\UrlBuilderException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\UrlBuilderException::TYPE_MISSING_BASE_URL
	 */
	public function testConstructorMissingBasePath()
	{
		new UrlBuilder('/path');
	}

	/**
	 * @dataProvider buildDataProvider
	 */
	public function testBuild($url, array $params, $expected_url)
	{
		$url_builder = new UrlBuilder($url);
		$actual_url = $url_builder->build($params);

		$this->assertSame($actual_url, $expected_url);
	}

	public function buildDataProvider()
	{
		return array(
			array(
				'http://domain.tld/path',
				array(),
				'http://domain.tld/path',
			),
			array(
				'http://domain.tld/path',
				array('param' => 'value'),
				'http://domain.tld/path?param=value',
			),
			array(
				'http://domain.tld/path?param=value',
				array(),
				'http://domain.tld/path?param=value',
			),
			array(
				'http://domain.tld/path?param1=value1',
				array('param2' => 'value2'),
				'http://domain.tld/path?param1=value1&param2=value2',
			),
			array(
				'http://domain.tld/path?param=value1',
				array('param' => 'value2'),
				'http://domain.tld/path?param=value2',
			),
			array(
				'http://domain.tld/path?param=value1#fragment',
				array('param' => 'value2'),
				'http://domain.tld/path?param=value2#fragment',
			),
		);
	}

}
