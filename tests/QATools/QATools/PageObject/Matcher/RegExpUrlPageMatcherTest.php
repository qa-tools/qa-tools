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


use mindplay\annotations\AnnotationManager;
use Mockery as m;
use QATools\QATools\PageObject\Matcher\RegExpUrlPageMatcher;
use QATools\QATools\PageObject\Page;
use tests\QATools\QATools\TestCase;

/**
 * Class FullUrlPageMatcherTest
 *
 * @package tests\QATools\QATools\PageObject\Matcher
 */
class RegExpUrlPageMatcherTest extends TestCase
{
	/**
	 * Class which should be returned by getMatcher.
	 */
	const PAGE_CLASS = '\\QATools\\QATools\\PageObject\\Page';

	/**
	 * Class for the annotation manager.
	 */
	const ANNOTATION_MANAGER_CLASS = '\\mindplay\\annotations\\AnnotationManager';

	/**
	 * @dataProvider matchesDataProvider
	 */
	public function testMatches($annotations, $url, $expected_matches)
	{
		/** @var Page $page */
		$page = m::mock(self::PAGE_CLASS);
		/** @var AnnotationManager $annotation_manager */
		$annotation_manager = m::mock(self::ANNOTATION_MANAGER_CLASS);
		$this->expectUrlMatchRegexpAnnotation($annotation_manager, $annotations);

		$matcher = new RegExpUrlPageMatcher();
		$matcher->register($annotation_manager, $this->session);

		$this->session->shouldReceive('getCurrentUrl')->andReturn($url);

		$this->assertEquals($expected_matches, $matcher->matches($page));
	}

	public function matchesDataProvider()
	{
		return array(
			array(
				array(array('regexp' => '/^\/relative$/')),
				'/relative',
				true,
			),
			array(
				array(array('regexp' => '/^\/not_matching$/')),
				'/relative',
				false,
			),
		);
	}

	/**
	 * @expectedException QATools\QATools\PageObject\Exception\PageMatcherException
	 * @expectedExceptionCode QATools\QATools\PageObject\Exception\PageMatcherException::TYPE_INCOMPLETE_ANNOTATION
	 * @expectedExceptionMessage url-match-regexp annotation not valid!
	 */
	public function testMatchesThrowsException()
	{
		/** @var Page $page */
		$page = m::mock(self::PAGE_CLASS);
		/** @var AnnotationManager $annotation_manager */
		$annotation_manager = m::mock(self::ANNOTATION_MANAGER_CLASS);
		$this->expectUrlMatchRegexpAnnotation($annotation_manager, array(null));

		$matcher = new RegExpUrlPageMatcher();
		$matcher->register($annotation_manager, $this->session);

		$this->session->shouldReceive('getCurrentUrl')->andReturn('/');

		$matcher->matches($page);
	}

}