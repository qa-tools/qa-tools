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


use Behat\Mink\Selector\CssSelector;
use Behat\Mink\Selector\Xpath\Escaper;
use QATools\QATools\PageObject\Exception\ElementException;

/**
 * Class for handling Selenium-style element selectors.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 *
 * @link http://bit.ly/qa-tools-findby-selector
 */
class SeleniumSelector
{

	/**
	 * Reference to CSS selector.
	 *
	 * @var CssSelector
	 */
	private $_cssSelector;

	/**
	 * The XPath escaper.
	 *
	 * @var Escaper
	 */
	private $_xpathEscaper;

	/**
	 * Creates instance of SeleniumSelector class.
	 */
	public function __construct()
	{
		$this->_cssSelector = new CssSelector();
		$this->_xpathEscaper = new Escaper();
	}

	/**
	 * Translates provided how/using combo into XPath.
	 *
	 * @param string $how   How class constant.
	 * @param string $using Using value.
	 *
	 * @return string
	 * @throws ElementException When given "$how" is not implemented.
	 * @throws ElementException When given "$using" is empty.
	 */
	public function translateToXPath($how, $using)
	{
		if ( empty($using) ) {
			throw new ElementException(
				'The "using" part the Selenium selector is empty',
				ElementException::TYPE_INCORRECT_SELECTOR
			);
		}

		$using = trim($using);

		if ( $how == How::CLASS_NAME ) {
			$using = $this->_xpathEscaper->escapeLiteral(' ' . $using . ' ');

			return "descendant-or-self::*[@class and contains(concat(' ', normalize-space(@class), ' '), " . $using . ')]';
		}
		elseif ( $how == How::CSS ) {
			return $this->_cssSelector->translateToXPath($using);
		}
		elseif ( $how == How::ID ) {
			return 'descendant-or-self::*[@id = ' . $this->_xpathEscaper->escapeLiteral($using) . ']';
		}
		elseif ( $how == How::NAME ) {
			return 'descendant-or-self::*[@name = ' . $this->_xpathEscaper->escapeLiteral($using) . ']';
		}
		elseif ( $how == How::ID_OR_NAME ) {
			$using = $this->_xpathEscaper->escapeLiteral($using);

			return 'descendant-or-self::*[@id = ' . $using . ' or @name = ' . $using . ']';
		}
		elseif ( $how == How::TAG_NAME ) {
			return 'descendant-or-self::' . $using;
		}
		elseif ( $how == How::LINK_TEXT ) {
			$using = $this->_xpathEscaper->escapeLiteral($using);

			return 'descendant-or-self::a[./@href][normalize-space(string(.)) = ' . $using . ']';
		}
		elseif ( $how == How::LABEL ) {
			$using = $this->_xpathEscaper->escapeLiteral($using);
			$xpath_pieces = array();
			$xpath_pieces[] = 'descendant-or-self::*[@id = (//label[normalize-space(string(.)) = ' . $using . ']/@for)]';
			$xpath_pieces[] = 'descendant-or-self::label[normalize-space(string(.)) = ' . $using . ']//input';

			return implode('|', $xpath_pieces);
		}
		elseif ( $how == How::PARTIAL_LINK_TEXT ) {
			$using = $this->_xpathEscaper->escapeLiteral($using);

			return 'descendant-or-self::a[./@href][contains(normalize-space(string(.)), ' . $using . ')]';
		}
		elseif ( $how == How::XPATH ) {
			return $using;
		}

		/*case How::LINK_TEXT:
		case How::PARTIAL_LINK_TEXT:*/

		throw new ElementException(
			sprintf('The "%s" how is not yet implemented', $how),
			ElementException::TYPE_UNKNOWN_SELECTOR
		);
	}

}
