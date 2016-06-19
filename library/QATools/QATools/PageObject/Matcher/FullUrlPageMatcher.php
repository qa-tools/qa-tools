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
class FullUrlPageMatcher implements IPageMatcher
{
	/**
	 * Matches the given url against the given annotations.
	 *
	 * @param string                   $url         The URL.
	 * @param UrlMatchFullAnnotation[] $annotations Given annotations.
	 *
	 * @return boolean
	 * @throws PageMatcherException When no matches specified.
	 */
	public function matches($url, array $annotations)
	{
		foreach ( $annotations as $annotation ) {
			if ( !$annotation->isValid() ) {
				throw new PageMatcherException(
					$this->getAnnotationName() . ' annotation not valid!',
					PageMatcherException::TYPE_INCOMPLETE_ANNOTATION
				);
			}

			if ( $url === $annotation->url ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns the name of the annotation.
	 *
	 * @return string
	 */
	public function getAnnotationName()
	{
		return 'url-match-full';
	}

	/**
	 * Returns the FQCN of the annotation.
	 *
	 * @return string
	 */
	public function getAnnotationClass()
	{
		return '\\QATools\\QATools\\PageObject\\Annotation\\UrlMatchFullAnnotation';
	}

}
