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
use QATools\QATools\PageObject\Annotation\UrlMatchComponentAnnotation;
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
		$this->annotationManager->registry[$page_matcher->getAnnotationName()] = $page_matcher->getAnnotationClass();

		$this->matchers[] = array('instance' => $page_matcher, 'priority' => $priority);

		usort($this->matchers, function ($matcher_a, $matcher_b) {
			return strcmp($matcher_a['priority'], $matcher_b['priority']);
		});

		return $this;
	}

	/**
	 * Matches the url against the given page.
	 *
	 * @param string $url  The URL.
	 * @param Page   $page Page to match.
	 *
	 * @return boolean
	 */
	public function match($url, Page $page)
	{
		foreach ( $this->matchers as $matcher ) {
			/* @var $annotations IUrlMatchAnnotation[] */
			$annotations = $this->annotationManager->getClassAnnotations(
				$page,
				'@' . $matcher['instance']->getAnnotationName()
			);

			if ( $matcher['instance']->matches($url, $annotations) ) {
				return true;
			}
		}

		return false;
	}

}
