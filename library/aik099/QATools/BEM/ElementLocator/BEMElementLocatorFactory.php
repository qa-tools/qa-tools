<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM\ElementLocator;


use aik099\QATools\PageObject\ElementLocator\DefaultElementLocatorFactory;
use aik099\QATools\PageObject\ElementLocator\IElementLocator;
use aik099\QATools\PageObject\ISearchContext;
use aik099\QATools\PageObject\Property;
use mindplay\annotations\AnnotationManager;

/**
 * Factory to create BEM block/element locators.
 *
 * @method \Mockery\Expectation shouldReceive
 */
class BEMElementLocatorFactory extends DefaultElementLocatorFactory
{

	/**
	 * Locator helper.
	 *
	 * @var LocatorHelper
	 */
	private $_locatorHelper;

	/**
	 * Create locator factory instance.
	 *
	 * @param ISearchContext    $search_context     Search context.
	 * @param AnnotationManager $annotation_manager Annotation manager.
	 * @param LocatorHelper     $locator_helper     Locator helper.
	 */
	public function __construct(ISearchContext $search_context, AnnotationManager $annotation_manager, LocatorHelper $locator_helper)
	{
		parent::__construct($search_context, $annotation_manager);
		$this->_locatorHelper = $locator_helper;
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
		return new BEMElementLocator($property, $this->searchContext, $this->annotationManager, $this->_locatorHelper);
	}

}
