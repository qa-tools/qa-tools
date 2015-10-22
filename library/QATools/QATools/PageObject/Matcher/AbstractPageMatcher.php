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
	 * Registers annotations, used by matcher.
	 *
	 * @param AnnotationManager $annotation_manager The annotation manager.
	 *
	 * @return self
	 */
	public function registerAnnotations(AnnotationManager $annotation_manager)
	{
		$this->annotationManager = $annotation_manager;

		return $this;
	}

}
