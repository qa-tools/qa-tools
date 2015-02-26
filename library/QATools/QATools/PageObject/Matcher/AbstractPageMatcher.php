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

/**
 * Abstract page matcher implementing register.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
abstract class AbstractPageMatcher implements IPageMatcher
{

	/**
	 * The annotation manager required to read annotations of the given page.
	 *
	 * @var AnnotationManager
	 */
	protected $annotationManager;

	/**
	 * Instance of Mink session.
	 *
	 * @var Session
	 */
	protected $session;

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
		$this->session = $session;
		$this->annotationManager = $annotation_manager;

		return $this;
	}

}
