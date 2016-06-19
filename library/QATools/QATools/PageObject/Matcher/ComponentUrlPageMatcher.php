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
use QATools\QATools\PageObject\Exception\PageMatcherException;
use QATools\QATools\PageObject\Page;
use QATools\QATools\PageObject\Url\Parser;

/**
 * Responsible for matching given pages against displayed page.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class ComponentUrlPageMatcher implements IPageMatcher
{
	/**
	 * Matches the given url against the given annotations.
	 *
	 * @param string                        $url         The URL.
	 * @param UrlMatchComponentAnnotation[] $annotations Given annotations.
	 *
	 * @return boolean
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

			if ( $this->matchComponent($annotation, $url) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Matches components to url.
	 *
	 * @param UrlMatchComponentAnnotation $annotation The used annotation.
	 * @param string                      $url        The current url.
	 *
	 * @return boolean
	 * @throws PageMatcherException When no matches specified.
	 */
	protected function matchComponent(UrlMatchComponentAnnotation $annotation, $url)
	{
		if ( !$annotation->isValid() ) {
			throw new PageMatcherException(
				$this->getAnnotationName() . ' annotation not valid!',
				PageMatcherException::TYPE_INCOMPLETE_ANNOTATION
			);
		}

		$parser = new Parser($url);

		return $this->matchPath($annotation, $parser)
			&& $this->matchParams($annotation, $parser)
			&& $this->matchSecure($annotation, $parser)
			&& $this->matchAnchor($annotation, $parser)
			&& $this->matchHost($annotation, $parser)
			&& $this->matchPort($annotation, $parser)
			&& $this->matchUser($annotation, $parser)
			&& $this->matchPass($annotation, $parser);
	}

	/**
	 * Matches path.
	 *
	 * @param UrlMatchComponentAnnotation $annotation The annotation.
	 * @param Parser                      $parser     Parser instance to match against.
	 *
	 * @return boolean
	 */
	protected function matchPath(UrlMatchComponentAnnotation $annotation, Parser $parser)
	{
		if ( !empty($annotation->path) && $annotation->path !== $parser->getComponent('path') ) {
			return false;
		}

		return true;
	}

	/**
	 * Matches query params.
	 *
	 * @param UrlMatchComponentAnnotation $annotation The annotation.
	 * @param Parser                      $parser     Parser instance to match against.
	 *
	 * @return boolean
	 */
	protected function matchParams(UrlMatchComponentAnnotation $annotation, Parser $parser)
	{
		if ( $annotation->params !== null ) {
			if ( $annotation->params != $parser->getParams() ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Matches secure option.
	 *
	 * @param UrlMatchComponentAnnotation $annotation The annotation.
	 * @param Parser                      $parser     Parser instance to match against.
	 *
	 * @return boolean
	 */
	protected function matchSecure(UrlMatchComponentAnnotation $annotation, Parser $parser)
	{
		if ( $annotation->secure !== null ) {
			$scheme = $annotation->secure ? 'https' : 'http';

			if ( $scheme !== $parser->getComponent('scheme') ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Matches anchor.
	 *
	 * @param UrlMatchComponentAnnotation $annotation The annotation.
	 * @param Parser                      $parser     Parser instance to match against.
	 *
	 * @return boolean
	 */
	protected function matchAnchor(UrlMatchComponentAnnotation $annotation, Parser $parser)
	{
		if ( !empty($annotation->anchor) && $annotation->anchor !== $parser->getComponent('fragment') ) {
			return false;
		}

		return true;
	}

	/**
	 * Matches host.
	 *
	 * @param UrlMatchComponentAnnotation $annotation The annotation.
	 * @param Parser                      $parser     Parser instance to match against.
	 *
	 * @return boolean
	 */
	protected function matchHost(UrlMatchComponentAnnotation $annotation, Parser $parser)
	{
		if ( !empty($annotation->host) && $annotation->host !== $parser->getComponent('host') ) {
			return false;
		}

		return true;
	}

	/**
	 * Matches port.
	 *
	 * @param UrlMatchComponentAnnotation $annotation The annotation.
	 * @param Parser                      $parser     Parser instance to match against.
	 *
	 * @return boolean
	 */
	protected function matchPort(UrlMatchComponentAnnotation $annotation, Parser $parser)
	{
		if ( !empty($annotation->port) && $annotation->port !== $parser->getComponent('port') ) {
			return false;
		}

		return true;
	}

	/**
	 * Matches user.
	 *
	 * @param UrlMatchComponentAnnotation $annotation The annotation.
	 * @param Parser                      $parser     Parser instance to match against.
	 *
	 * @return boolean
	 */
	protected function matchUser(UrlMatchComponentAnnotation $annotation, Parser $parser)
	{
		if ( !empty($annotation->user) && $annotation->user !== $parser->getComponent('user') ) {
			return false;
		}

		return true;
	}

	/**
	 * Matches pass.
	 *
	 * @param UrlMatchComponentAnnotation $annotation The annotation.
	 * @param Parser                      $parser     Parser instance to match against.
	 *
	 * @return boolean
	 */
	protected function matchPass(UrlMatchComponentAnnotation $annotation, Parser $parser)
	{
		if ( !empty($annotation->pass) && $annotation->pass !== $parser->getComponent('pass') ) {
			return false;
		}

		return true;
	}

	/**
	 * Returns the name of the annotation.
	 *
	 * @return string
	 */
	public function getAnnotationName()
	{
		return 'url-match-component';
	}

	/**
	 * Returns the FQCN of the annotation.
	 *
	 * @return string
	 */
	public function getAnnotationClass()
	{
		return '\\QATools\\QATools\\PageObject\\Annotation\\UrlMatchComponentAnnotation';
	}

}
