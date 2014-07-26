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


use QATools\QATools\PageObject\Page;
use QATools\QATools\PageObject\Url\IBuilder;
use QATools\QATools\PageObject\Url\IUrlFactory;
use Mockery as m;
use tests\QATools\QATools\TestCase;

class PageTest extends TestCase
{

	const URL_BUILDER_INTERFACE = '\\QATools\\QATools\\PageObject\\Url\\IBuilder';

	/**
	 * Page class.
	 *
	 * @var string
	 */
	protected $pageClass = '\\tests\\QATools\\QATools\\PageObject\\Fixture\\Page\\PageChild';

	/**
	 * Page.
	 *
	 * @var Page
	 */
	protected $page;

	/**
	 * UrlFactory class.
	 *
	 * @var string
	 */
	protected $urlFactoryClass = '\\QATools\\QATools\\PageObject\\Url\\UrlFactory';

	/**
	 * The url builder factory.
	 *
	 * @var IUrlFactory
	 */
	protected $urlBuilderFactory;

	protected function setUp()
	{
		parent::setUp();

		$this->pageFactory->shouldReceive('initPage')->once()->andReturn($this->pageFactory);
		$this->pageFactory->shouldReceive('initElements')->once()->andReturn($this->pageFactory);

		$decorator = m::mock('\\QATools\\QATools\\PageObject\\PropertyDecorator\\IPropertyDecorator');
		$this->pageFactory->shouldReceive('createDecorator')->once()->andReturn($decorator);

		$this->urlBuilderFactory = m::mock('\\QATools\\QATools\\PageObject\\Url\\IUrlFactory');

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

		$this->session->shouldReceive('visit')->with($expected)->once();

		$this->page->setUrlBuilder($url_builder);

		$this->assertSame($this->page, $this->page->open());
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\PageException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\PageException::TYPE_EMPTY_URL
	 * @expectedExceptionMessage Page url not specified
	 */
	public function testOpenIncorrectUrlBuilder()
	{
		$url_builder = $this->createUrlBuilderWithReturn();

		$this->page->setUrlBuilder($url_builder);

		$this->page->open();
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\PageException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\PageException::TYPE_MISSING_URL_BUILDER
	 * @expectedExceptionMessage The url builder of a page not set, have you used @page-url annotation?
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
		/* @var IUrlFactory $url_builder_factory */
		$url_builder_factory = new $this->urlFactoryClass();

		$this->session->shouldReceive('visit')->with($expected)->once();
		$this->page->setUrlBuilder($url_builder_factory->getBuilder(parse_url($url)));

		$this->assertSame($this->page, $this->page->open($params));
	}

	/**
	 * Creates an empty mocked url builder.
	 *
	 * @return IBuilder
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
	 * @return IBuilder
	 */
	public function createUrlBuilderWithReturn($return = null)
	{
		$url_builder = $this->createUrlBuilder();

		$url_builder->shouldReceive('build')->once()->andReturn($return);

		return $url_builder;
	}

}
