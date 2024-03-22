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


use mindplay\annotations\AnnotationManager;
use QATools\QATools\PageObject\Annotation\IMatchUrlAnnotation;
use QATools\QATools\PageObject\Exception\PageUrlMatcherException;
use QATools\QATools\PageObject\Page;

/**
 * Contains matchers and matches pages.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class PageUrlMatcherRegistry
{

	/**
	 * Annotation manager.
	 *
	 * @var AnnotationManager
	 */
	protected $annotationManager;

	/**
	 * Registered page url matchers.
	 *
	 * @var IPageUrlMatcher[]
	 */
	protected $matchers = array();

	/**
	 * Creates PageUrlMatcherRegistry instance.
	 *
	 * @param AnnotationManager $annotation_manager Annotation manager.
	 */
	public function __construct(AnnotationManager $annotation_manager)
	{
		$this->annotationManager = $annotation_manager;
	}

	/**
	 * Adds a page url matcher instance.
	 *
	 * @param IPageUrlMatcher $page_url_matcher Page url matcher.
	 *
	 * @return static
	 * @throws PageUrlMatcherException When page url matcher with same priority is already registered.
	 */
	public function add(IPageUrlMatcher $page_url_matcher)
	{
		$priority = (string)$page_url_matcher->getPriority();

		if ( isset($this->matchers[$priority]) ) {
			throw new PageUrlMatcherException(
				'The page url matcher with "' . $priority . '" priority is already registered.',
				PageUrlMatcherException::TYPE_DUPLICATE_PRIORITY
			);
		}

		$this->matchers[$priority] = $page_url_matcher;
		$this->annotationManager
			->registry[$page_url_matcher->getAnnotationName()] = $page_url_matcher->getAnnotationClass();

		krsort($this->matchers, SORT_NUMERIC);

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
		foreach ( $this->matchers as $page_url_matcher ) {
			$annotation_name = '@' . $page_url_matcher->getAnnotationName();
			$annotations = $this->annotationManager->getClassAnnotations($page, $annotation_name);

			if ( !$annotations ) {
				continue;
			}

			$this->ensureAnnotationsAreValid($annotations, $annotation_name);

			if ( $page_url_matcher->matches($url, $annotations) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks that all annotations are valid.
	 *
	 * @param IMatchUrlAnnotation[] $annotations     Annotations.
	 * @param string                $annotation_name Annotation name.
	 *
	 * @return void
	 * @throws PageUrlMatcherException When annotations are not valid.
	 */
	protected function ensureAnnotationsAreValid(array $annotations, $annotation_name)
	{
		foreach ( $annotations as $annotation ) {
			if ( !$annotation->isValid() ) {
				throw new PageUrlMatcherException(
					'The "' . $annotation_name . '" annotation is not valid.',
					PageUrlMatcherException::TYPE_INVALID_ANNOTATION
				);
			}
		}
	}

}
