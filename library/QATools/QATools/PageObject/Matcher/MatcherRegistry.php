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
 * Contains matchers and matches pages.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class MatcherRegistry
{

	/**
	 * Annotation manager.
	 *
	 * @var AnnotationManager
	 */
	protected $annotationManager;

	/**
	 * Current active page matchers.
	 *
	 * @var IPageMatcher[]
	 */
	protected $matchers = array();

	/**
	 * Creates MatcherRegistry instance.
	 *
	 * @param AnnotationManager $annotation_manager Annotation manager.
	 */
	public function __construct(AnnotationManager $annotation_manager)
	{
		$this->annotationManager = $annotation_manager;
	}

	/**
	 * Adds a page matcher instance.
	 *
	 * @param IPageMatcher $page_matcher The page matcher instance.
	 * @param integer      $priority     Priority of the matcher.
	 *
	 * @return self
	 */
	public function add(IPageMatcher $page_matcher, $priority = 0)
	{
		$page_matcher->registerAnnotations($this->annotationManager);

		$this->matchers[] = array('instance' => $page_matcher, 'priority' => $priority);

		usort($this->matchers, function ($a, $b) {
			return strcmp($a['priority'], $b['priority']);
		});

		return $this;
	}

	/**
	 * Matches the page against registered matchers.
	 *
	 * @param Page   $page Page to match.
	 * @param string $url  The URL.
	 *
	 * @return boolean
	 */
	public function match(Page $page, $url)
	{
		foreach ( $this->matchers as $matcher ) {
			if ( $matcher['instance']->matches($page, $url) ) {
				return true;
			}
		}

		return false;
	}

}
