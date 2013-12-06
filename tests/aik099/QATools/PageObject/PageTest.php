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


use Mockery as m;
use aik099\QATools\PageObject\Page;
use tests\aik099\QATools\TestCase;

class PageTest extends TestCase
{

	/**
	 * Page class.
	 *
	 * @var string
	 */
	protected $pageClass = '\\tests\\aik099\\QATools\\PageObject\\Fixture\\Pages\\PageChild';

	/**
	 * Page.
	 *
	 * @var Page
	 */
	protected $page;

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

		$decorator = m::mock('\\aik099\\QATools\\PageObject\\PropertyDecorators\\IPropertyDecorator');
		$this->pageFactory->shouldReceive('createDecorator')->once()->andReturn($decorator);

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

		$this->page->relativeUrl = $expected;
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
		$this->session->shouldReceive('visit')->with($expected)->once()->andReturnNull();

		$this->page->relativeUrl = $expected;
		$this->assertSame($this->page, $this->page->open());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \RuntimeException
	 */
	public function testOpenIncorrect()
	{
		$this->page->open();
	}

}
