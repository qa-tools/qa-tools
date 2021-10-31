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
use QATools\QATools\PageObject\Annotation\MatchUrlRegexpAnnotation;

class RegexpPageUrlMatcherTest extends AbstractPageUrlMatcherTestCase
{

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		$this->className = '\\QATools\\QATools\\PageObject\\PageUrlMatcher\\RegexpPageUrlMatcher';

		parent::setUpTest();
	}

	public function testPriority()
	{
		$this->assertEquals(2, $this->pageUrlMatcher->getPriority());
	}

	public function testGetAnnotationName()
	{
		$this->assertEquals('match-url-regexp', $this->pageUrlMatcher->getAnnotationName());
	}

	public function testGetAnnotationClass()
	{
		$this->assertEquals(
			'\\QATools\\QATools\\PageObject\\Annotation\\MatchUrlRegexpAnnotation',
			$this->pageUrlMatcher->getAnnotationClass()
		);
	}

	/**
	 * Creates annotation instance, that matcher will use.
	 *
	 * @param array $parameters Parameters.
	 *
	 * @return MatchUrlRegexpAnnotation
	 */
	public function createAnnotation(array $parameters)
	{
		$annotation = new MatchUrlRegexpAnnotation();
		$annotation->initAnnotation($parameters);

		return $annotation;
	}

	public function matchesDataProvider()
	{
		return array(
			'matches' => array(
				array(array('regexp' => '/^\/relative$/')),
				'/relative',
				true,
			),
			'not matches' => array(
				array(array('regexp' => '/^\/not_matching$/')),
				'/relative',
				false,
			),
			'matches at least one' => array(
				array(array('regexp' => '/^\/not_matching$/'), array('regexp' => '/^\/relative$/')),
				'/relative',
				true,
			),
		);
	}

}
