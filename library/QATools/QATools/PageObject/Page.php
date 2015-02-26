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


use QATools\QATools\PageObject\Exception\PageException;
use QATools\QATools\PageObject\Url\IBuilder;
use Behat\Mink\Element\DocumentElement;

/**
 * The base class to be used for making classes representing pages, that can contain WebElements.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
abstract class Page extends DocumentElement implements ISearchContext
{

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
	 * Initialize the page.
	 *
	 * @param IPageFactory $page_factory Page factory.
	 */
	public function __construct(IPageFactory $page_factory)
	{
		parent::__construct($page_factory->getSession());

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

		$this->getSession()->visit($url);

		return $this;
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
	 * Checks if the page is opened based on the specified url match.
	 *
	 * @return boolean
	 */
	public function opened()
	{
		return $this->pageFactory->opened($this);
	}

}
