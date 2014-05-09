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

	/**
	 * Prepares page.
	 *
	 * @return void
	 */
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

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testConstructor()
	{
		$this->assertSame($this->session, $this->page->getSession());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetAbsoluteUrl()
	{
		$expected = 'RL';
		$url_builder = $this->createUrlBuilderWithReturn($expected);

		$this->page->setUrlBuilder($url_builder);

		$this->assertEquals($expected, $this->page->getAbsoluteUrl());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testOpenCorrect()
	{
		$expected = 'RL';
		$url_builder = $this->createUrlBuilderWithReturn($expected);

		$this->session->shouldReceive('visit')->with($expected)->once()->andReturnNull();

		$this->page->setUrlBuilder($url_builder);

		$this->assertSame($this->page, $this->page->open());
	}

	/**
	 * Test with an url builder that returns no url.
	 *
	 * @return void
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
	 * Test open with missing url builder.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\PageObject\Exception\PageException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\PageException::TYPE_MISSING_URL_BUILDER
	 */
	public function testOpenMissingUrlBuilder()
	{
		$this->page->open();
	}

	/**
	 * Tests if params are correctly added to the URL.
	 *
	 * @param string $expected The expected merged url.
	 * @param array  $params   The GET params.
	 *
	 * @dataProvider getAbsoluteUrlWithParamsDataProvider
	 * @return void
	 */
	public function testGetAbsoluteUrlWithParams($expected, array $params)
	{
		$url_builder = $this->createUrlBuilder();

		$url_builder->shouldReceive('build')->with($params)->once()->andReturn($expected);

		$this->page->setUrlBuilder($url_builder);

		$this->assertEquals($expected, $this->page->getAbsoluteUrl($params));
	}

	/**
	 * Data Provider for the GET param test.
	 *
	 * @return array
	 */
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

	/**
	 * Test that open is still working if params are passed.
	 *
	 * @return void
	 */
	public function testOpenWithParamsCorrect()
	{
		$url = 'RL';
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
