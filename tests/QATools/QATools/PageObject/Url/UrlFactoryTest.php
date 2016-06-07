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
use QATools\QATools\PageObject\Url\UrlFactory;
use Mockery as m;
use tests\QATools\QATools\TestCase;

/**
 * Class UrlFactoryTest
 *
 * @package tests\QATools\QATools\PageObject\Url
 */
class UrlFactoryTest extends TestCase
{
	/**
	 * Class which should be returned by getBuilder
	 */
	const BUILDER_INTERFACE = '\\QATools\\QATools\\PageObject\\Url\\IBuilder';

	/**
	 * Class which should be returned by the factory
	 */
	const PARSER_INTERFACE = '\\QATools\\QATools\\PageObject\\Url\\Parser';

	public function testGetBuilder()
	{
		$factory = new UrlFactory();
		$normalized_components = array(
			'scheme' => 'http',
			'host' => 'domain.tld',
			'path' => '/path',
			'query' => 'param=value',
			'fragment' => 'anchor',
		);

		/** @var Builder $builder */
		$builder = $factory->getBuilder($normalized_components);

		$this->assertInstanceOf(self::BUILDER_INTERFACE, $builder);
	}

}
