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


use aik099\QATools\PageObject\Annotation\PageUrlAnnotation;
use aik099\QATools\PageObject\Url\Normalizer;
use Mockery as m;
use tests\aik099\QATools\TestCase;

class NormalizerTest extends TestCase
{

	const URL_FACTORY_INTERFACE = '\\aik099\\QATools\\PageObject\\Url\\UrlFactory';

	const URL_PARSER_CLASS = '\\aik099\\QATools\\PageObject\\Url\\Parser';

	/**
	 * @dataProvider normalizeDataProvider
	 */
	public function testNormalize($base_url, $url, $secure, $expected_normalized_components)
	{
		$normalizer = new Normalizer($base_url);

		$page_url_annotation = new PageUrlAnnotation();
		$page_url_annotation->url = $url;
		$page_url_annotation->secure = $secure;

		$this->assertEquals($expected_normalized_components, $normalizer->normalize($page_url_annotation));
	}

	public function normalizeDataProvider()
	{
		return array(
			// Missing base url.
			array(
				'',
				'http://domain.tld/path?param=value#anchor',
				null,
				array(
					'scheme' => 'http',
					'host' => 'domain.tld',
					'path' => '/path',
					'query' => 'param=value',
					'fragment' => 'anchor',
				),
			),
			// Defined base url.
			array(
				'http://base.tld',
				'/path?param=value#anchor',
				null,
				array(
					'scheme' => 'http',
					'host' => 'base.tld',
					'path' => '/path',
					'query' => 'param=value',
					'fragment' => 'anchor',
				),
			),
			// Defined secure mode.
			array(
				'http://base.tld',
				'/path?param=value#anchor',
				true,
				array(
					'scheme' => 'https',
					'host' => 'base.tld',
					'path' => '/path',
					'query' => 'param=value',
					'fragment' => 'anchor',
				),
			),
			array(
				'https://base.tld',
				'/path?param=value#anchor',
				false,
				array(
					'scheme' => 'http',
					'host' => 'base.tld',
					'path' => '/path',
					'query' => 'param=value',
					'fragment' => 'anchor',
				),
			),
			// Merge priority.
			array(
				'http://base.tld/path',
				'https://another.tld/path2?param=value#anchor',
				null,
				array(
					'scheme' => 'https',
					'host' => 'another.tld',
					'path' => '/path2',
					'query' => 'param=value',
					'fragment' => 'anchor',
				),
			),
		);
	}

}
