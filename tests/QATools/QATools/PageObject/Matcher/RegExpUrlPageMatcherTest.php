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
use QATools\QATools\PageObject\Annotation\UrlMatchRegexpAnnotation;
use QATools\QATools\PageObject\Matcher\RegExpUrlPageMatcher;
use tests\QATools\QATools\TestCase;

class RegExpUrlPageMatcherTest extends TestCase
{
	/**
	 * @dataProvider matchesDataProvider
	 */
	public function testMatches($annotations, $url, $expected_matches)
	{
		$parsed_annotations = array();

		foreach ($annotations as $annotation) {
			$parsed_annotation = new UrlMatchRegexpAnnotation();
			$parsed_annotation->initAnnotation($annotation);
			$parsed_annotations[] = $parsed_annotation;
		}

		$matcher = new RegExpUrlPageMatcher();

		$this->assertEquals($expected_matches, $matcher->matches($url, $parsed_annotations));
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
			array(
				array(array('regexp' => '/^\/not_matching$/'), array('regexp' => '/^\/relative$/')),
				'/relative',
				true,
			),
		);
	}

}
