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
	 * Instance of Mink session.
	 *
	 * @var Session
	 */
	protected $session;

	/**
	 * Annotation manager.
	 *
	 * @var AnnotationManager
	 */
	protected $annotationManager;

	/**
	 * The current config.
	 *
	 * @var array
	 */
	protected $matchers = array();

	/**
	 * Current active page matchers.
	 *
	 * @var IPageMatcher[]
	 */
	protected $pageMatchers = array();

	/**
	 * Creates PageFactory instance.
	 *
	 * @param AnnotationManager $annotation_manager Annotation manager.
	 * @param Session           $session            Current session.
	 * @param array             $matchers           Array of FQCN of matchers.
	 */
	public function __construct(AnnotationManager $annotation_manager, Session $session, array $matchers = array())
	{
		$this->annotationManager = $annotation_manager;
		$this->session = $session;

		foreach ( $matchers as $index => $matcher ) {
			$this->registerMatcher($matcher, $index);
		}
	}

	/**
	 * Registers new matcher.
	 *
	 * @param string  $page_matcher The matcher FQCN.
	 * @param integer $priority     Priority of the matcher.
	 *
	 * @return self
	 */
	public function registerMatcher($page_matcher, $priority = 0)
	{
		$this->matchers[] = array($page_matcher, $priority);

		return $this;
	}

	/**
	 * Initializes page matchers.
	 *
	 * @return self
	 */
	public function initialize()
	{
		if ( count($this->matchers) === count($this->pageMatchers) ) {
			return $this;
		}

		usort($this->matchers, function ($a, $b) {
			if ( $a[1] === $b[1] ) {
				return 0;
			}

			return ($a[1] < $b[1]) ? -1 : 1;
		});

		$this->pageMatchers = array();

		foreach ( $this->matchers as $matcher ) {
			/* @var $instance IPageMatcher */
			$instance = is_string($matcher[0]) ? new $matcher[0]() : $matcher[0];
			$instance->register($this->annotationManager, $this->session);

			$this->pageMatchers[] = $instance;
		}

		return $this;
	}

	/**
	 * Matches the page against registered matchers.
	 *
	 * @param Page $page Page to match.
	 *
	 * @return boolean
	 */
	public function match(Page $page)
	{
		foreach ( $this->pageMatchers as $matcher ) {
			if ( $matcher->matches($page) ) {
				return true;
			}
		}

		return false;
	}

}
