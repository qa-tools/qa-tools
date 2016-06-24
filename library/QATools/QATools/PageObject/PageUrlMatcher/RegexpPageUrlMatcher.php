<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\PageUrlMatcher;


use QATools\QATools\PageObject\Annotation\MatchUrlRegexpAnnotation;

/**
 * Checks, that page is opened by comparing url using a regular expression.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class RegexpPageUrlMatcher implements IPageUrlMatcher
{

	/**
	 * Returns matcher priority.
	 *
	 * @return float
	 */
	public function getPriority()
	{
		return 2;
	}

	/**
	 * Returns the name of the annotation.
	 *
	 * @return string
	 */
	public function getAnnotationName()
	{
		return 'match-url-regexp';
	}

	/**
	 * Returns the FQCN of the annotation.
	 *
	 * @return string
	 */
	public function getAnnotationClass()
	{
		return '\\QATools\\QATools\\PageObject\\Annotation\\MatchUrlRegexpAnnotation';
	}

	/**
	 * Matches the given url against the given annotations.
	 *
	 * @param string                     $url         The URL.
	 * @param MatchUrlRegexpAnnotation[] $annotations Given annotations.
	 *
	 * @return boolean
	 */
	public function matches($url, array $annotations)
	{
		foreach ( $annotations as $annotation ) {
			if ( preg_match($annotation->regexp, $url) ) {
				return true;
			}
		}

		return false;
	}

}
