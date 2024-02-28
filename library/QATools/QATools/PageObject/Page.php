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


use Behat\Mink\Element\NodeElement;
use QATools\QATools\PageObject\Exception\ElementException;
use QATools\QATools\PageObject\Exception\PageException;
use QATools\QATools\PageObject\Url\IBuilder;
use Behat\Mink\Element\DocumentElement;

/**
 * The base class to be used for making classes representing pages, that can contain WebElements.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 *
 * @method NodeElement|null findById($id) Finds element by its id.
 * @method boolean hasLink($locator) Checks whether element has a link with specified locator.
 * @method NodeElement|null findLink($locator) Finds link with specified locator.
 * @method void clickLink($locator) Clicks link with specified locator.
 * @method boolean hasButton($locator) Checks whether element has a button (input[type=submit|image|button|reset], button) with specified locator.
 * @method NodeElement|null findButton($locator) Finds button (input[type=submit|image|button|reset], button) with specified locator.
 * @method void pressButton($locator) Presses button (input[type=submit|image|button|reset], button) with specified locator.
 * @method boolean hasField($locator) Checks whether element has a field (input, textarea, select) with specified locator.
 * @method NodeElement|null findField($locator) Finds field (input, textarea, select) with specified locator.
 * @method void fillField($locator, $value) Fills in field (input, textarea, select) with specified locator.
 * @method boolean hasCheckedField($locator) Checks whether element has a checkbox with specified locator, which is checked.
 * @method boolean hasUncheckedField($locator) Checks whether element has a checkbox with specified locator, which is unchecked.
 * @method void checkField($locator) Checks checkbox with specified locator.
 * @method void uncheckField($locator) Unchecks checkbox with specified locator.
 * @method boolean hasSelect($locator) Checks whether element has a select field with specified locator.
 * @method void selectFieldOption($locator, $value, $multiple = false) Selects option from select field with specified locator.
 * @method boolean hasTable($locator) Checks whether element has a table with specified locator.
 * @method void attachFileToField($locator, $path) Attach file to file field with specified locator.
 *
 * @method boolean has($selector, $locator) Checks whether element with specified selector exists inside the current element.
 * @method boolean isValid() Checks if an element still exists in the DOM.
 * @method string getText() Returns element text (inside tag).
 * @method string getHtml() Returns element inner html.
 * @method string getOuterHtml() Returns element outer html.
 */
abstract class Page implements ISearchContext
{

	use TWrappedElement;

	/**
	 * Page factory, used to create a Page.
	 *
	 * @var IPageFactory
	 */
	protected $pageFactory = null;

	/**
	 * The builder which generates the url.
	 *
	 * @var IBuilder
	 */
	protected $urlBuilder;

	/**
	 * Wrapped element.
	 *
	 * @var DocumentElement
	 */
	private $_wrappedElement;

	/**
	 * Initialize the page.
	 *
	 * @param IPageFactory $page_factory Page factory.
	 */
	public function __construct(IPageFactory $page_factory)
	{
		$this->_wrappedElement = new DocumentElement($page_factory->getSession());

		$this->pageFactory = $page_factory;

		$this->pageFactory->initPage($this)->initElements($this, $this->pageFactory->createDecorator($this));
	}

	/**
	 * Returns full url to the page with a possibility to alter it real time.
	 *
	 * @param array $params Page parameters.
	 *
	 * @return string
	 * @throws PageException When url builder is missing.
	 */
	public function getAbsoluteUrl(array $params = array())
	{
		if ( !is_object($this->urlBuilder) ) {
			throw new PageException(
				'The url builder of a page not set, have you used @page-url annotation?',
				PageException::TYPE_MISSING_URL_BUILDER
			);
		}

		return $this->urlBuilder->build($params);
	}

	/**
	 * Opens this page in browser.
	 *
	 * @param array $params Page parameters.
	 *
	 * @return self
	 * @throws PageException When page url not specified.
	 */
	public function open(array $params = array())
	{
		$url = $this->getAbsoluteUrl($params);

		if ( !$url ) {
			throw new PageException('Page url not specified', PageException::TYPE_EMPTY_URL);
		}

		return $this->setCurrentUrl($url);
	}

	/**
	 * Sets the url builder.
	 *
	 * @param IBuilder $url_builder Url builder.
	 *
	 * @return self
	 */
	public function setUrlBuilder(IBuilder $url_builder)
	{
		$this->urlBuilder = $url_builder;

		return $this;
	}

	/**
	 * Returns url of the current page.
	 *
	 * Overriding this method would allow operating on a page within a frameset.
	 *
	 * @return string
	 */
	public function getCurrentUrl()
	{
		return $this->pageFactory->getSession()->getCurrentUrl();
	}

	/**
	 * Sets url of the current page.
	 *
	 * Overriding this method would allow operating on a page within a frameset.
	 *
	 * @param string $url URL.
	 *
	 * @return self
	 */
	protected function setCurrentUrl($url)
	{
		$this->pageFactory->getSession()->visit($url);

		return $this;
	}

	/**
	 * Checks if the page is currently opened in browser.
	 *
	 * @return boolean
	 */
	public function opened()
	{
		return $this->pageFactory->opened($this);
	}

}
