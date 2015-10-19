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
use QATools\QATools\PageObject\Annotation\UrlMatchRegexpAnnotation;
use QATools\QATools\PageObject\Exception\PageMatcherException;
use QATools\QATools\PageObject\Page;

/**
 * Responsible for matching given pages against displayed page.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class RegExpUrlPageMatcher extends AbstractPageMatcher
{
	const ANNOTATION = 'url-match-regexp';

	/**
	 * Initializes the Page Matcher.
	 *
	 * @param AnnotationManager $annotation_manager The annotation manager.
	 * @param Session           $session            The current mink session.
	 *
	 * @return self
	 */
	public function register(AnnotationManager $annotation_manager, Session $session)
	{
		parent::register($annotation_manager, $session);

		$this->annotationManager->registry[self::ANNOTATION] = '\\QATools\\QATools\\PageObject\\Annotation\\UrlMatchRegexpAnnotation';

		return $this;
	}

	/**
	 * Matches the given page against the open.
	 *
	 * @param Page $page Page to match.
	 *
	 * @return boolean
	 * @throws PageMatcherException When no matches specified.
	 */
	public function matches(Page $page)
	{
		/* @var $annotations UrlMatchRegexpAnnotation[] */
		$annotations = $this->annotationManager->getClassAnnotations($page, '@' . self::ANNOTATION);

		$url = $this->session->getCurrentUrl();

		foreach ( $annotations as $annotation ) {
			if ( !$annotation->isValid() ) {
				throw new PageMatcherException(
					self::ANNOTATION . ' annotation not valid!',
					PageMatcherException::TYPE_INCOMPLETE_ANNOTATION
				);
			}

			if ( preg_match($annotation->regexp, $url) ) {
				return true;
			}
		}

		return false;
	}

}
