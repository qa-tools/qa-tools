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


use aik099\QATools\PageObject\Url\Builder;
use aik099\QATools\PageObject\Url\UrlFactory;
use Mockery as m;
use tests\aik099\QATools\TestCase;

/**
 * Class UrlFactoryTest
 *
 * @package tests\aik099\QATools\PageObject\Url
 */
class UrlFactoryTest extends TestCase
{
	/**
	 * Class which should be returned by getBuilder
	 */
	const BUILDER_INTERFACE = '\\aik099\\QATools\\PageObject\\Url\\IBuilder';

	/**
	 * Class which should be returned by the factory
	 */
	const PARSER_INTERFACE = '\\aik099\\QATools\\PageObject\\Url\\Parser';

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
