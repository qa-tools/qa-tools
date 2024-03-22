<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\BEM\ElementLocator;


use QATools\QATools\PageObject\ElementLocator\DefaultElementLocatorFactory;
use QATools\QATools\PageObject\ElementLocator\IElementLocator;
use QATools\QATools\PageObject\ISearchContext;
use QATools\QATools\PageObject\Property;
use QATools\QATools\PageObject\SeleniumSelector;

/**
 * Factory to create BEM block/element locators.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
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
	 * @param ISearchContext   $search_context    Search context.
	 * @param SeleniumSelector $selenium_selector Selenium selector.
	 * @param LocatorHelper    $locator_helper    Locator helper.
	 */
	public function __construct(
		ISearchContext $search_context,
		SeleniumSelector $selenium_selector,
		LocatorHelper $locator_helper
	) {
		parent::__construct($search_context, $selenium_selector);
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
		return new BEMElementLocator($property, $this->searchContext, $this->seleniumSelector, $this->_locatorHelper);
	}

}
