<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\ElementLocator;


use QATools\QATools\PageObject\ISearchContext;
use QATools\QATools\PageObject\Property;
use QATools\QATools\PageObject\SeleniumSelector;

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
	 * Selenium selector.
	 *
	 * @var SeleniumSelector
	 */
	protected $seleniumSelector;

	/**
	 * Create locator factory instance.
	 *
	 * @param ISearchContext   $search_context    Search context.
	 * @param SeleniumSelector $selenium_selector Selenium selector.
	 */
	public function __construct(ISearchContext $search_context, SeleniumSelector $selenium_selector)
	{
		$this->searchContext = $search_context;
		$this->seleniumSelector = $selenium_selector;
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
		return new WaitingElementLocator($property, $this->searchContext, $this->seleniumSelector);
	}

}
