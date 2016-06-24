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


use QATools\QATools\PageObject\Annotation\IMatchUrlAnnotation;
use QATools\QATools\PageObject\Exception\PageUrlMatcherException;

/**
 * Responsible for matching given pages against displayed page.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
interface IPageUrlMatcher
{

	/**
	 * Returns matcher priority.
	 *
	 * @return float
	 */
	public function getPriority();

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

	/**
	 * Matches the given url against the given annotations.
	 *
	 * @param string                $url         The URL.
	 * @param IMatchUrlAnnotation[] $annotations Given annotations.
	 *
	 * @return boolean
	 */
	public function matches($url, array $annotations);

}
