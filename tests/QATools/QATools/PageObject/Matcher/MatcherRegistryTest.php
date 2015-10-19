<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Matcher;


use Mockery as m;
use QATools\QATools\PageObject\Matcher\MatcherRegistry;
use QATools\QATools\PageObject\Page;
use tests\QATools\QATools\TestCase;

class MatcherRegistryTest extends TestCase
{
	const ANNOTATION_MANAGER_CLASS = '\\mindplay\\annotations\\AnnotationManager';

	const MATCHER_INTERFACE = '\\QATools\\QATools\\PageObject\\Matcher\\IPageMatcher';

	const MATCHER_TRUE = '\\tests\\QATools\\QATools\\PageObject\\Fixture\\Matcher\\AlwaysMatchMatcher';

	/**
	 * Annotation manager.
	 *
	 * @var \Mockery\MockInterface
	 */
	protected $annotationManager;

	protected function setUp()
	{
		parent::setUp();

		$this->annotationManager = m::mock(self::ANNOTATION_MANAGER_CLASS);
	}

	public function testConstructor()
	{
		$registry = new MatcherRegistry($this->annotationManager, $this->session);

		$this->assertInstanceOf('\\QATools\\QATools\\PageObject\\Matcher\\MatcherRegistry', $registry);
	}

	public function testRegisterMatcherOrderAsc()
	{
		$registry = new MatcherRegistry($this->annotationManager, $this->session);

		$matcher1 = $this->createMatcher();
		$matcher2 = $this->createMatcher();

		$registry->registerMatcher($matcher1, 1);
		$registry->registerMatcher($matcher2, 2);

		$this->assertEquals($registry, $registry->initialize());
	}

	public function testRegisterMatcherOrderDesc()
	{
		$registry = new MatcherRegistry($this->annotationManager, $this->session);

		$matcher1 = $this->createMatcher();
		$matcher2 = $this->createMatcher();

		$registry->registerMatcher($matcher2, 2);
		$registry->registerMatcher($matcher1, 1);

		$this->assertEquals($registry, $registry->initialize());
	}

	public function testRegisterMatcherOrderEqual()
	{
		$registry = new MatcherRegistry($this->annotationManager, $this->session);

		$matcher1 = $this->createMatcher();
		$matcher2 = $this->createMatcher(false);
		$matcher3 = $this->createMatcher(false);
		$matcher4 = $this->createMatcher();

		$registry->registerMatcher($matcher3, 0);
		$registry->registerMatcher($matcher4, 1);
		$registry->registerMatcher($matcher2, 0);
		$registry->registerMatcher($matcher1, -1);

		$this->assertEquals($registry, $registry->initialize());
	}

	protected function createMatcher($ordered = true)
	{
		$matcher = m::mock(self::MATCHER_INTERFACE);

		if ( $ordered ) {
			$matcher->shouldReceive('register')->once()->andReturnSelf()->globally()->ordered();
		}
		else {
			$matcher->shouldReceive('register')->once()->andReturnSelf();
		}

		return $matcher;
	}

	public function testMatchTrue()
	{
		/** @var Page $page */
		$page = m::mock('\\QATools\\QATools\\PageObject\\Page');

		$registry = new MatcherRegistry($this->annotationManager, $this->session);

		$registry->registerMatcher(self::MATCHER_TRUE, 0);
		$registry->initialize();

		$this->assertTrue($registry->match($page));
	}

	public function testMatchEmptyFalse()
	{
		/** @var Page $page */
		$page = m::mock('\\QATools\\QATools\\PageObject\\Page');

		$registry = new MatcherRegistry($this->annotationManager, $this->session);

		$registry->initialize();

		$this->assertFalse($registry->match($page));
	}

}
