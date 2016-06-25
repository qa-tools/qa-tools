<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject;


use Mockery as m;
use QATools\QATools\PageObject\Annotation\PageUrlAnnotation;
use QATools\QATools\PageObject\Config\Config;
use QATools\QATools\PageObject\Container;
use QATools\QATools\PageObject\PageLocator\DefaultPageLocator;
use QATools\QATools\PageObject\Url\Normalizer;

class ContainerTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Container.
	 *
	 * @var Container
	 */
	protected $container;

	protected function setUp()
	{
		parent::setUp();

		$this->container = new Container();
	}

	public function testUserOptions()
	{
		$this->assertEmpty($this->container['config_options']);
	}

	public function testConfigUsesUserOptions()
	{
		$this->container['config_options'] = array(
			'base_url' => 'http://www.domain.com/',
		);

		/** @var Config $config */
		$config = $this->container['config'];

		$this->assertEquals('http://www.domain.com/', $config->getOption('base_url'));
	}

	public function testConfigUsesDefaultOptions()
	{
		/** @var Config $config */
		$config = $this->container['config'];

		$this->assertEquals('', $config->getOption('base_url'));
	}

	public function testAnnotationManager()
	{
		$this->assertInstanceOf('\\mindplay\\annotations\\AnnotationManager', $this->container['annotation_manager']);
	}

	public function testUrlFactory()
	{
		$this->assertInstanceOf('\\QATools\\QATools\\PageObject\\Url\\UrlFactory', $this->container['url_factory']);
	}

	public function testUrlNormalizer()
	{
		$this->container['config_options'] = array(
			'base_url' => 'http://www.domain.com/',
		);

		/** @var Normalizer $url_normalizer */
		$url_normalizer = $this->container['url_normalizer'];

		$this->assertInstanceOf('\\QATools\\QATools\\PageObject\\Url\\Normalizer', $url_normalizer);

		$page_url_annotation = new PageUrlAnnotation();
		$page_url_annotation->url = '/relative';

		$this->assertSame(
			array(
				'scheme' => 'http',
				'host' => 'www.domain.com',
				'path' => '/relative',
				'query' => '',
			),
			$url_normalizer->normalize($page_url_annotation)
		);
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\PageFactoryException
	 * @expectedExceptionMessage None of the possible classes were found: \MatrixPage
	 */
	public function testPageLocatorDefaultOptions()
	{
		/** @var DefaultPageLocator $page_locator */
		$page_locator = $this->container['page_locator'];

		$this->assertInstanceOf('\\QATools\\QATools\\PageObject\\PageLocator\\DefaultPageLocator', $page_locator);

		$page_locator->resolvePage('Matrix Page');
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\PageFactoryException
	 * @expectedExceptionMessage None of the possible classes were found: \MatrixNS\MatrixPage
	 */
	public function testPageLocatorUserOptions()
	{
		$this->container['config_options'] = array(
			'page_namespace_prefix' => 'MatrixNS\\',
		);

		/** @var DefaultPageLocator $page_locator */
		$page_locator = $this->container['page_locator'];

		$this->assertInstanceOf('\\QATools\\QATools\\PageObject\\PageLocator\\DefaultPageLocator', $page_locator);

		$page_locator->resolvePage('Matrix Page');
	}

	public function testPageUrlMatcherRegistry()
	{
		$this->assertInstanceOf(
			'\\QATools\\QATools\\PageObject\\PageUrlMatcher\\PageUrlMatcherRegistry',
			$this->container['page_url_matcher_registry']
		);
	}

}
