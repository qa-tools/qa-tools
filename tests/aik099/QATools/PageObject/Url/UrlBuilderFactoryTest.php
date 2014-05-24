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
use aik099\QATools\PageObject\Url\UrlBuilderFactory;
use Mockery as m;
use tests\aik099\QATools\TestCase;

/**
 * Class UrlBuilderFactoryTest
 *
 * @package tests\aik099\QATools\PageObject\Url
 */
class UrlBuilderFactoryTest extends TestCase
{
	/**
	 * Class which should be returned by the factory
	 */
	const URL_BUILDER_INTERFACE = '\\aik099\\QATools\\PageObject\\Url\\IUrlBuilder';

	/**
	 * @dataProvider getUrlBuilderDataProvider
	 */
	public function testGetUrlBuilder(
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
		$factory = new UrlBuilderFactory();

		/* @var UrlBuilder $url_builder */
		$url_builder = $factory->getUrlBuilder($url, $params, $base_url);

		$this->assertInstanceOf(self::URL_BUILDER_INTERFACE, $url_builder);

		$this->assertEquals($expected_protocol, $url_builder->getProtocol());
		$this->assertEquals($expected_host, $url_builder->getHost());
		$this->assertEquals($expected_path, $url_builder->getPath());
		$this->assertEquals($expected_params, $url_builder->getParams());
		$this->assertEquals($expected_anchor, $url_builder->getAnchor());
	}

	public function getUrlBuilderDataProvider()
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
				'/path#anchor',
				array(),
				'http://domain.tld',
				'http',
				'domain.tld',
				'/path',
				array(),
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
				'/path#anchor',
				array('param' => 'value'),
				'http://domain.tld',
				'http',
				'domain.tld',
				'/path',
				array('param' => 'value'),
				'anchor',
			),
		);
	}

}
