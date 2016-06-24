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


use QATools\QATools\PageObject\Annotation\MatchUrlComponentAnnotation;
use QATools\QATools\PageObject\Url\Parser;

/**
 * Checks, that page is opened by comparing individual url components.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class ComponentPageUrlMatcher implements IPageUrlMatcher
{

	/**
	 * Returns matcher priority.
	 *
	 * @return float
	 */
	public function getPriority()
	{
		return 1;
	}

	/**
	 * Returns the name of the annotation.
	 *
	 * @return string
	 */
	public function getAnnotationName()
	{
		return 'match-url-component';
	}

	/**
	 * Returns the FQCN of the annotation.
	 *
	 * @return string
	 */
	public function getAnnotationClass()
	{
		return '\\QATools\\QATools\\PageObject\\Annotation\\MatchUrlComponentAnnotation';
	}

	/**
	 * Matches the given url against the given annotations.
	 *
	 * @param string                        $url         The URL.
	 * @param MatchUrlComponentAnnotation[] $annotations Given annotations.
	 *
	 * @return boolean
	 */
	public function matches($url, array $annotations)
	{
		foreach ( $annotations as $annotation ) {
			if ( $this->matchComponent($annotation, $url) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Matches components to url.
	 *
	 * @param MatchUrlComponentAnnotation $annotation The used annotation.
	 * @param string                      $url        The current url.
	 *
	 * @return boolean
	 */
	protected function matchComponent(MatchUrlComponentAnnotation $annotation, $url)
	{
		$parser = new Parser($url);

		return $this->matchByProperty($annotation, $parser, 'path')
			&& $this->matchParams($annotation, $parser)
			&& $this->matchSecure($annotation, $parser)
			&& $this->matchByProperty($annotation, $parser, 'anchor', 'fragment')
			&& $this->matchByProperty($annotation, $parser, 'host')
			&& $this->matchByProperty($annotation, $parser, 'port')
			&& $this->matchByProperty($annotation, $parser, 'user')
			&& $this->matchByProperty($annotation, $parser, 'pass');
	}

	/**
	 * Matches query params.
	 *
	 * @param MatchUrlComponentAnnotation $annotation The annotation.
	 * @param Parser                      $parser     Parser instance to match against.
	 *
	 * @return boolean
	 */
	protected function matchParams(MatchUrlComponentAnnotation $annotation, Parser $parser)
	{
		// Not specified means match anything.
		if ( !isset($annotation->params) ) {
			return true;
		}

		return $parser->getParams() == $annotation->params;
	}

	/**
	 * Matches secure option.
	 *
	 * @param MatchUrlComponentAnnotation $annotation The annotation.
	 * @param Parser                      $parser     Parser instance to match against.
	 *
	 * @return boolean
	 */
	protected function matchSecure(MatchUrlComponentAnnotation $annotation, Parser $parser)
	{
		// Not specified means match anything.
		if ( !isset($annotation->secure) ) {
			return true;
		}

		return $parser->getComponent('scheme') === ($annotation->secure ? 'https' : 'http');
	}

	/**
	 * Matches property.
	 *
	 * @param MatchUrlComponentAnnotation $annotation The annotation.
	 * @param Parser                      $parser     Parser instance to match against.
	 * @param string                      $property   Property name.
	 * @param string|null                 $component  Component name.
	 *
	 * @return boolean
	 */
	protected function matchByProperty(
		MatchUrlComponentAnnotation $annotation,
		Parser $parser,
		$property,
		$component = null
	) {
		// Not specified means match anything.
		if ( !isset($annotation->$property) ) {
			return true;
		}

		return $parser->getComponent(isset($component) ? $component : $property) === $annotation->$property;
	}

}
