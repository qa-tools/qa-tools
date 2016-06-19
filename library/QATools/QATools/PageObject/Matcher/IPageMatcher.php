<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\Matcher;


use Behat\Mink\Session;
use mindplay\annotations\AnnotationManager;
use QATools\QATools\PageObject\Page;

/**
 * Responsible for matching given pages against displayed page.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
interface IPageMatcher
{

	/**
	 * Matches the given url against the given annotations.
	 *
	 * @param string         $url         The URL.
	 * @param IPageMatcher[] $annotations Given annotations.
	 *
	 * @return boolean
	 */
	public function matches($url, array $annotations);

	/**
	 * Returns the name of the annotation.
	 * 
	 * @return string
	 */
	public function getAnnotationName();

	/**
	 * Returns the FQCN of the annotation.
	 *
	 * @return string
	 */
	public function getAnnotationClass();

}
