<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject;


use Behat\Mink\Session;
use QATools\QATools\PageObject\Element\IElementContainer;
use QATools\QATools\PageObject\PropertyDecorator\IPropertyDecorator;

/**
 * All page factories must implement this interface.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
interface IPageFactory
{

	/**
	 * Returns element session.
	 *
	 * @return Session
	 */
	public function getSession();

	/**
	 * Creates default decorator.
	 *
	 * @param ISearchContext $search_context Search context.
	 *
	 * @return IPropertyDecorator
	 */
	public function createDecorator(ISearchContext $search_context);

	/**
	 * Initializes the page.
	 *
	 * @param Page $page Page to initialize.
	 *
	 * @return static
	 */
	public function initPage(Page $page);

	/**
	 * Initializes AbstractElementContainer.
	 *
	 * @param IElementContainer $element_container AbstractElementContainer to be initialized.
	 *
	 * @return static
	 */
	public function initElementContainer(IElementContainer $element_container);

	/**
	 * Initializes elements in given search context.
	 *
	 * @param ISearchContext     $search_context     Context, to be used for element initialization.
	 * @param IPropertyDecorator $property_decorator Element locator factory.
	 *
	 * @return static
	 */
	public function initElements(ISearchContext $search_context, IPropertyDecorator $property_decorator);

	/**
	 * Creates page by given class name.
	 *
	 * @param string $class_name Page class name.
	 *
	 * @return Page
	 */
	public function getPage($class_name);

	/**
	 * Checks if the given page is currently opened in browser.
	 *
	 * @param Page $page Page to check.
	 *
	 * @return boolean
	 */
	public function opened(Page $page);

	/**
	 * Translates provided how/using combo into XPath.
	 *
	 * @param string $how   How class constant.
	 * @param string $using Using value.
	 *
	 * @return string
	 */
	public function translateToXPath($how, $using);

}
