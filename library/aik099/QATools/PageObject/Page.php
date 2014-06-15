<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject;


use aik099\QATools\PageObject\Exception\PageException;
use aik099\QATools\PageObject\Url\IUrlBuilder;
use Behat\Mink\Element\DocumentElement;

/**
 * The base class to be used for making classes representing pages, that can contain WebElements.
 *
 * @method \Mockery\Expectation shouldReceive()
 */
abstract class Page extends DocumentElement implements ISearchContext
{

	/**
	 * The builder which generates the url.
	 *
	 * @var IUrlBuilder
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

		$page_factory->initPage($this)->initElements($this, $page_factory->createDecorator($this));
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
				'The UrlBuilder of a page not set, have you used @page-url annotation?',
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
	 * @param IUrlBuilder $urlBuilder Url builder.
	 *
	 * @return self
	 */
	public function setUrlBuilder(IUrlBuilder $urlBuilder)
	{
		$this->urlBuilder = $urlBuilder;

		return $this;
	}

}
