<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\PageUrlMatcher;


use Mockery as m;
use QATools\QATools\PageObject\PageUrlMatcher\PageUrlMatcherRegistry;
use QATools\QATools\PageObject\Page;
use tests\QATools\QATools\TestCase;

class PageUrlMatcherRegistryTest extends TestCase
{

	const ANNOTATION_MANAGER_CLASS = '\\mindplay\\annotations\\AnnotationManager';

	const MATCHER_INTERFACE = '\\QATools\\QATools\\PageObject\\PageUrlMatcher\\IPageUrlMatcher';

	/**
	 * Annotation manager.
	 *
	 * @var \Mockery\MockInterface
	 */
	protected $annotationManager;

	/**
	 * Page url matcher registry.
	 *
	 * @var PageUrlMatcherRegistry
	 */
	protected $pageUrlMatcherRegistry;

	/**
	 * The page.
	 *
	 * @var Page
	 */
	protected $page;

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		parent::setUpTest();

		$this->annotationManager = m::mock(self::ANNOTATION_MANAGER_CLASS);
		$this->pageUrlMatcherRegistry = new PageUrlMatcherRegistry($this->annotationManager);

		$this->page = m::mock('\\QATools\\QATools\\PageObject\\Page');
	}

	public function testConstructor()
	{
		$this->assertInstanceOf(
			'\\QATools\\QATools\\PageObject\\PageUrlMatcher\\PageUrlMatcherRegistry',
			$this->pageUrlMatcherRegistry
		);
	}

	/**
	 * @return void
	 * @throws \QATools\QATools\PageObject\Exception\PageUrlMatcherException
	 */
	public function testUniqueMatcherPriorityChecked()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\PageUrlMatcherException');
		$this->expectExceptionMessage('The page url matcher with "1" priority is already registered.');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\PageUrlMatcherException::TYPE_DUPLICATE_PRIORITY);

		$matcher1 = m::mock(self::MATCHER_INTERFACE);
		$matcher1->shouldReceive('getPriority')->andReturn(1);
		$matcher1->shouldReceive('getAnnotationName')->andReturn('a');
		$matcher1->shouldReceive('getAnnotationClass')->andReturn('a');

		$matcher2 = m::mock(self::MATCHER_INTERFACE);
		$matcher2->shouldReceive('getPriority')->andReturn(1);
		$matcher2->shouldReceive('getAnnotationName')->andReturn('a');
		$matcher2->shouldReceive('getAnnotationClass')->andReturn('a');

		$this->pageUrlMatcherRegistry->add($matcher1);
		$this->pageUrlMatcherRegistry->add($matcher2);
	}

	public function testMatchingWithoutAnnotations()
	{
		$matcher = m::mock(self::MATCHER_INTERFACE);
		$matcher->shouldReceive('getPriority')->andReturn(1);
		$matcher->shouldReceive('getAnnotationName')->andReturn('a');
		$matcher->shouldReceive('getAnnotationClass')->andReturn('a');

		$this->annotationManager->shouldReceive('getClassAnnotations')->with($this->page, '@a')->andReturn(array());

		$this->pageUrlMatcherRegistry->add($matcher);
		$this->assertFalse($this->pageUrlMatcherRegistry->match('/', $this->page));
	}

	public function testMatchingWithoutMatchers()
	{
		$this->assertFalse($this->pageUrlMatcherRegistry->match('/', $this->page));
	}

	public function testMatchersAreOrderedByPriority()
	{
		$annotation_class = '\\QATools\\QATools\\PageObject\\Annotation\\IMatchUrlAnnotation';
		$annotation = m::mock($annotation_class);
		$annotation->shouldReceive('isValid')->andReturn(true);
		$annotations = array($annotation);

		$matcher1 = m::mock(self::MATCHER_INTERFACE);
		$matcher1->shouldReceive('getPriority')->andReturn(1);
		$matcher1->shouldReceive('getAnnotationName')->andReturn('one');
		$matcher1->shouldReceive('getAnnotationClass')->andReturn($annotation_class);
		$matcher1->shouldReceive('matches')->with('/', $annotations)->never();

		$matcher2 = m::mock(self::MATCHER_INTERFACE);
		$matcher2->shouldReceive('getPriority')->andReturn(2);
		$matcher2->shouldReceive('getAnnotationName')->andReturn('one');
		$matcher2->shouldReceive('getAnnotationClass')->andReturn($annotation_class);
		$matcher2->shouldReceive('matches')->with('/', $annotations)->once()->andReturn(true);

		$this->pageUrlMatcherRegistry->add($matcher1);
		$this->pageUrlMatcherRegistry->add($matcher2);

		$this->annotationManager
			->shouldReceive('getClassAnnotations')
			->with($this->page, '@one')
			->andReturn($annotations);

		$this->assertTrue($this->pageUrlMatcherRegistry->match('/', $this->page));
	}

	public function testInvalidAnnotations()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\PageUrlMatcherException');
		$this->expectExceptionMessage('The "@one" annotation is not valid.');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\PageUrlMatcherException::TYPE_INVALID_ANNOTATION);

		$annotation_class = '\\QATools\\QATools\\PageObject\\Annotation\\IMatchUrlAnnotation';
		$annotation = m::mock($annotation_class);
		$annotation->shouldReceive('isValid')->andReturn(false);
		$annotations = array($annotation);

		$matcher = m::mock(self::MATCHER_INTERFACE);
		$matcher->shouldReceive('getPriority')->andReturn(1);
		$matcher->shouldReceive('getAnnotationName')->andReturn('one');
		$matcher->shouldReceive('getAnnotationClass')->andReturn($annotation_class);
		$matcher->shouldReceive('matches')->with('/', $annotations)->never();

		$this->pageUrlMatcherRegistry->add($matcher);

		$this->annotationManager
			->shouldReceive('getClassAnnotations')
			->with($this->page, '@one')
			->andReturn($annotations);

		$this->pageUrlMatcherRegistry->match('/', $this->page);
	}

}
