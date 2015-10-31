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
	 * Initializes page matcher.
	 *
	 * @param AnnotationManager $annotation_manager The annotation manager.
	 *
	 * @return self
	 */
	public function registerAnnotations(AnnotationManager $annotation_manager);

	/**
	 * Matches the given page against the displayed page.
	 *
	 * @param Page   $page Page to match.
	 * @param string $url  The URL.
	 *
	 * @return boolean
	 */
	public function matches(Page $page, $url);

}
