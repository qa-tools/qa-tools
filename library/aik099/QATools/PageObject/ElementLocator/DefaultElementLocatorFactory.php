<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\ElementLocator;


use mindplay\annotations\AnnotationManager;
use aik099\QATools\PageObject\ISearchContext;
use aik099\QATools\PageObject\Property;

/**
 * Factory, that creates locators for finding WebElements.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class DefaultElementLocatorFactory implements IElementLocatorFactory
{

	/**
	 * Search context.
	 *
	 * @var ISearchContext
	 */
	protected $searchContext;

	/**
	 * Page factory.
	 *
	 * @var AnnotationManager
	 */
	protected $annotationManager;

	/**
	 * Create locator factory instance.
	 *
	 * @param ISearchContext    $search_context     Search context.
	 * @param AnnotationManager $annotation_manager Annotation manager.
	 */
	public function __construct(ISearchContext $search_context, AnnotationManager $annotation_manager)
	{
		$this->searchContext = $search_context;
		$this->annotationManager = $annotation_manager;
	}

	/**
	 * When a field on a class needs to be decorated with an IElementLocator this method will be called.
	 *
	 * @param Property $property Property.
	 *
	 * @return IElementLocator
	 */
	public function createLocator(Property $property)
	{
		return new WaitingElementLocator($property, $this->searchContext, $this->annotationManager);
	}

}
