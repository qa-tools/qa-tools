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
use QATools\QATools\PageObject\Matcher\FullUrlPageMatcher;
use QATools\QATools\PageObject\Page;
use tests\QATools\QATools\TestCase;

class FullUrlPageMatcherTest extends TestCase
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
		$this->expectUrlMatchFullAnnotation($annotation_manager, $annotations);

		$matcher = new FullUrlPageMatcher();
		$matcher->registerAnnotations($annotation_manager);

		$this->assertEquals($expected_matches, $matcher->matches($page, $url));
	}

	public function matchesDataProvider()
	{
		return array(
			array(
				array(array('url' => '/relative')),
				'/relative',
				true,
			),
			array(
				array(array('url' => '/not_matching')),
				'/relative',
				false,
			),
			array(
				array(array('url' => '/not_matching'), array('url' => '/relative')),
				'/relative',
				true,
			),
		);
	}

	/**
	 * @expectedException QATools\QATools\PageObject\Exception\PageMatcherException
	 * @expectedExceptionCode QATools\QATools\PageObject\Exception\PageMatcherException::TYPE_INCOMPLETE_ANNOTATION
	 * @expectedExceptionMessage url-match-full annotation not valid!
	 */
	public function testMatchesThrowsException()
	{
		/** @var Page $page */
		$page = m::mock(self::PAGE_CLASS);
		/** @var AnnotationManager $annotation_manager */
		$annotation_manager = m::mock(self::ANNOTATION_MANAGER_CLASS);
		$this->expectUrlMatchFullAnnotation($annotation_manager, array(null));

		$matcher = new FullUrlPageMatcher();
		$matcher->registerAnnotations($annotation_manager);

		$matcher->matches($page, '/');
	}

}
