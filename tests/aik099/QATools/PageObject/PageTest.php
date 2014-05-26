<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\PageObject;


use aik099\QATools\PageObject\Page;
use aik099\QATools\PageObject\Url\IUrlBuilder;
use aik099\QATools\PageObject\Url\IUrlBuilderFactory;
use Mockery as m;
use tests\aik099\QATools\TestCase;

class PageTest extends TestCase
{

	const URL_BUILDER_INTERFACE = '\\aik099\\QATools\\PageObject\\Url\\IUrlBuilder';

	/**
	 * Page class.
	 *
	 * @var string
	 */
	protected $pageClass = '\\tests\\aik099\\QATools\\PageObject\\Fixture\\Page\\PageChild';

	/**
	 * Page.
	 *
	 * @var Page
	 */
	protected $page;

	/**
	 * UrlBuilderFactory class.
	 *
	 * @var string
	 */
	protected $urlBuilderFactoryClass = '\\aik099\\QATools\\PageObject\\Url\\UrlBuilderFactory';

	/**
	 * The url builder factory.
	 *
	 * @var IUrlBuilderFactory
	 */
	protected $urlBuilderFactory;

	protected function setUp()
	{
		parent::setUp();

		$this->pageFactory->shouldReceive('initPage')->once()->andReturn($this->pageFactory);
		$this->pageFactory->shouldReceive('initElements')->once()->andReturn($this->pageFactory);

		$decorator = m::mock('\\aik099\\QATools\\PageObject\\PropertyDecorator\\IPropertyDecorator');
		$this->pageFactory->shouldReceive('createDecorator')->once()->andReturn($decorator);

		$this->urlBuilderFactory = m::mock('\\aik099\\QATools\\PageObject\\Url\\IUrlBuilderFactory');

		$this->page = new $this->pageClass($this->pageFactory);
	}

	public function testConstructor()
	{
		$this->assertSame($this->session, $this->page->getSession());
	}

	public function testGetAbsoluteUrl()
	{
		$expected = 'RL';
		$url_builder = $this->createUrlBuilderWithReturn($expected);

		$this->page->setUrlBuilder($url_builder);

		$this->assertEquals($expected, $this->page->getAbsoluteUrl());
	}

	public function testOpenCorrect()
	{
		$expected = 'RL';
		$url_builder = $this->createUrlBuilderWithReturn($expected);

		$this->session->shouldReceive('visit')->with($expected)->once()->andReturnNull();

		$this->page->setUrlBuilder($url_builder);

		$this->assertSame($this->page, $this->page->open());
	}

	/**
	 * @expectedException \aik099\QATools\PageObject\Exception\PageException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\PageException::TYPE_EMPTY_URL
	 */
	public function testOpenIncorrectUrlBuilder()
	{
		$url_builder = $this->createUrlBuilderWithReturn();

		$this->page->setUrlBuilder($url_builder);

		$this->page->open();
	}

	/**
	 * @expectedException \aik099\QATools\PageObject\Exception\PageException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\PageException::TYPE_MISSING_URL_BUILDER
	 */
	public function testOpenMissingUrlBuilder()
	{
		$this->page->open();
	}

	/**
	 * @dataProvider getAbsoluteUrlWithParamsDataProvider
	 */
	public function testGetAbsoluteUrlWithParams($expected, array $params)
	{
		$url_builder = $this->createUrlBuilder();

		$url_builder->shouldReceive('build')->with($params)->once()->andReturn($expected);

		$this->page->setUrlBuilder($url_builder);

		$this->assertEquals($expected, $this->page->getAbsoluteUrl($params));
	}

	public function getAbsoluteUrlWithParamsDataProvider()
	{
		return array(
			array(
				'RL?param1=value1&param2=value2',
				array('param1' => 'value1', 'param2' => 'value2'),
			),
			array(
				'RL?param=value&param1=value1&param2=value2',
				array('param1' => 'value1', 'param2' => 'value2'),
			),
		);
	}

	public function testOpenWithParamsCorrect()
	{
		$url = 'http://domain.tld/RL';
		$expected = $url . '?param=value';
		$params = array('param' => 'value');
		/* @var IUrlBuilderFactory $url_builder_factory */
		$url_builder_factory = new $this->urlBuilderFactoryClass();

		$this->session->shouldReceive('visit')->with($expected)->once()->andReturnNull();

		$this->page->setUrlBuilder($url_builder_factory->getUrlBuilder($url));

		$this->assertSame($this->page, $this->page->open($params));
	}

	/**
	 * Creates an empty mocked url builder.
	 *
	 * @return IUrlBuilder
	 */
	public function createUrlBuilder()
	{
		return m::mock(self::URL_BUILDER_INTERFACE);
	}

	/**
	 * Creates a mocked url builder with once expected called build function and passed return value.
	 *
	 * @param mixed $return The return value of mocked build function.
	 *
	 * @return IUrlBuilder
	 */
	public function createUrlBuilderWithReturn($return = null)
	{
		$url_builder = $this->createUrlBuilder();

		$url_builder->shouldReceive('build')->once()->andReturn($return);

		return $url_builder;
	}

}
