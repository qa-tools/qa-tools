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
use QATools\QATools\PageObject\Annotation\UrlMatchFullAnnotation;
use QATools\QATools\PageObject\Exception\PageMatcherException;
use QATools\QATools\PageObject\Page;

/**
 * Responsible for matching given pages against displayed page.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class FullUrlPageMatcher extends AbstractPageMatcher
{
	const ANNOTATION = 'url-match-full';

	/**
	 * Registers annotations, used by matcher.
	 *
	 * @param AnnotationManager $annotation_manager The annotation manager.
	 *
	 * @return self
	 */
	public function registerAnnotations(AnnotationManager $annotation_manager)
	{
		parent::registerAnnotations($annotation_manager);

		$this->annotationManager->registry[self::ANNOTATION] = '\\QATools\\QATools\\PageObject\\Annotation\\UrlMatchFullAnnotation';

		return $this;
	}

	/**
	 * Matches the given page against the open.
	 *
	 * @param Page   $page Page to match.
	 * @param String $url  The URL.
	 *
	 * @return boolean
	 * @throws PageMatcherException When no matches specified.
	 */
	public function matches(Page $page, $url)
	{
		/* @var $annotations UrlMatchFullAnnotation[] */
		$annotations = $this->annotationManager->getClassAnnotations($page, '@' . self::ANNOTATION);

		foreach ( $annotations as $annotation ) {
			if ( !$annotation->isValid() ) {
				throw new PageMatcherException(
					self::ANNOTATION . ' annotation not valid!',
					PageMatcherException::TYPE_INCOMPLETE_ANNOTATION
				);
			}

			if ( $url === $annotation->url ) {
				return true;
			}
		}

		return false;
	}

}
