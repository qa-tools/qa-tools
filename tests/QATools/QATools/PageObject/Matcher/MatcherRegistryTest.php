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

	/**
	 * The page.
	 *
	 * @var Page
	 */
	protected $page;

	protected function setUp()
	{
		parent::setUp();

		$this->annotationManager = m::mock(self::ANNOTATION_MANAGER_CLASS);

		$this->page = m::mock('\\QATools\\QATools\\PageObject\\Page');
	}

	public function testConstructor()
	{
		$registry = new MatcherRegistry($this->annotationManager);

		$this->assertInstanceOf('\\QATools\\QATools\\PageObject\\Matcher\\MatcherRegistry', $registry);
	}

	public function testMatchMatcherOrderAsc()
	{
		$registry = new MatcherRegistry($this->annotationManager);

		$matcher1 = $this->createMatcher();
		$matcher2 = $this->createMatcher();

		$registry->add($matcher1, 1);
		$registry->add($matcher2, 2);

		$this->annotationManager->shouldReceive('getClassAnnotations')->andReturn(array());

		$this->assertFalse($registry->match('/', $this->page));
	}

	public function testMatchMatcherOrderDesc()
	{
		$registry = new MatcherRegistry($this->annotationManager);

		$matcher1 = $this->createMatcher();
		$matcher2 = $this->createMatcher();

		$registry->add($matcher2, 2);
		$registry->add($matcher1, 1);

		$this->annotationManager->shouldReceive('getClassAnnotations')->andReturn(array());

		$this->assertFalse($registry->match('/', $this->page));
	}

	public function testMatchMatcherOrderEqual()
	{
		$registry = new MatcherRegistry($this->annotationManager);

		$matcher1 = $this->createMatcher();
		$matcher2 = $this->createMatcher(false);
		$matcher3 = $this->createMatcher(false);
		$matcher4 = $this->createMatcher();

		$registry->add($matcher3, 0);
		$registry->add($matcher4, 1);
		$registry->add($matcher2, 0);
		$registry->add($matcher1, -1);

		$this->annotationManager->shouldReceive('getClassAnnotations')->andReturn(array());

		$this->assertFalse($registry->match('/', $this->page));
	}

	protected function createMatcher($orderedMatch = true)
	{
		$matcher = m::mock(self::MATCHER_INTERFACE);

		$matcher->shouldReceive('getAnnotationName')->twice()->andReturn('mocked-annotation');
		$matcher->shouldReceive('getAnnotationClass')->once()->andReturn('MockedAnnotation');

		if ( $orderedMatch ) {
			$matcher->shouldReceive('matches')->once()->andReturn(false)->globally()->ordered();
		}
		else {
			$matcher->shouldReceive('matches')->once()->andReturn(false);
		}

		return $matcher;
	}

	public function testMatchTrue()
	{
		$registry = new MatcherRegistry($this->annotationManager);

		$instance_name = self::MATCHER_TRUE;

		$registry->add(new $instance_name, 0);

		$this->annotationManager->shouldReceive('getClassAnnotations')->andReturn(array());

		$this->assertTrue($registry->match('/', $this->page));
	}

	public function testMatchEmptyFalse()
	{
		$registry = new MatcherRegistry($this->annotationManager);

		$this->annotationManager->shouldReceive('getClassAnnotations')->andReturn(array());

		$this->assertFalse($registry->match('/', $this->page));
	}

}
