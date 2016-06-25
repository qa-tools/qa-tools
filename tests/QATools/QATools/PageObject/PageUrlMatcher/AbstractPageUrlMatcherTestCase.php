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
use QATools\QATools\PageObject\Annotation\IMatchUrlAnnotation;
use QATools\QATools\PageObject\PageUrlMatcher\IPageUrlMatcher;
use tests\QATools\QATools\TestCase;

abstract class AbstractPageUrlMatcherTestCase extends TestCase
{

	/**
	 * Page url matcher class.
	 *
	 * @var string
	 */
	protected $className;

	/**
	 * Page url matcher.
	 *
	 * @var IPageUrlMatcher
	 */
	protected $pageUrlMatcher;

	protected function setUp()
	{
		$this->pageUrlMatcher = new $this->className();

		parent::setUp();
	}

	abstract public function testPriority();

	abstract public function testGetAnnotationName();

	abstract public function testGetAnnotationClass();

	/**
	 * @dataProvider matchesDataProvider
	 */
	public function testMatches($annotations, $url, $expected_matches)
	{
		$parsed_annotations = array();

		foreach ( $annotations as $annotation_params ) {
			$parsed_annotations[] = $this->createAnnotation($annotation_params);
		}

		$this->assertEquals($expected_matches, $this->pageUrlMatcher->matches($url, $parsed_annotations));
	}

	abstract public function matchesDataProvider();

	/**
	 * Creates annotation instance, that matcher will use.
	 *
	 * @param array $parameters Parameters.
	 *
	 * @return IMatchUrlAnnotation
	 */
	abstract public function createAnnotation(array $parameters);

}
