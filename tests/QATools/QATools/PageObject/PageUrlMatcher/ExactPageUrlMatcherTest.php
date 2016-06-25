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
use QATools\QATools\PageObject\Annotation\MatchUrlExactAnnotation;

class ExactPageUrlMatcherTest extends AbstractPageUrlMatcherTestCase
{

	protected function setUp()
	{
		$this->className = '\\QATools\\QATools\\PageObject\\PageUrlMatcher\\ExactPageUrlMatcher';

		parent::setUp();
	}

	public function testPriority()
	{
		$this->assertEquals(3, $this->pageUrlMatcher->getPriority());
	}

	public function testGetAnnotationName()
	{
		$this->assertEquals('match-url-exact', $this->pageUrlMatcher->getAnnotationName());
	}

	public function testGetAnnotationClass()
	{
		$this->assertEquals(
			'\\QATools\\QATools\\PageObject\\Annotation\\MatchUrlExactAnnotation',
			$this->pageUrlMatcher->getAnnotationClass()
		);
	}

	/**
	 * Creates annotation instance, that matcher will use.
	 *
	 * @param array $parameters Parameters.
	 *
	 * @return MatchUrlExactAnnotation
	 */
	public function createAnnotation(array $parameters)
	{
		$annotation = new MatchUrlExactAnnotation();
		$annotation->initAnnotation($parameters);

		return $annotation;
	}

	public function matchesDataProvider()
	{
		return array(
			array(
				'matches' => array(array('url' => '/relative')),
				'/relative',
				true,
			),
			'not matches' => array(
				array(array('url' => '/not_matching')),
				'/relative',
				false,
			),
			'matches at least once' => array(
				array(array('url' => '/not_matching'), array('url' => '/relative')),
				'/relative',
				true,
			),
		);
	}

}
