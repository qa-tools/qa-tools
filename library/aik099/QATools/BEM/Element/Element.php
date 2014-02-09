<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM\Element;


use aik099\QATools\PageObject\Element\IWebElement;
use aik099\QATools\PageObject\Element\WebElement;

/**
 * BEM element.
 *
 * @method \Mockery\Expectation shouldReceive
 */
class Element implements IElement
{

	/**
	 * Name of the element.
	 *
	 * @var string
	 */
	private $_name;

	/**
	 * Wrapped element.
	 *
	 * @var WebElement
	 */
	private $_wrappedElement;

	/**
	 * Specifies wrapped WebElement and element's name.
	 *
	 * @param string      $name            Element name.
	 * @param IWebElement $wrapped_element Wrapped element.
	 */
	public function __construct($name, IWebElement $wrapped_element)
	{
		$this->_name = $name;
		$this->_wrappedElement = $wrapped_element;
	}

	/**
	 * Returns wrapped element.
	 *
	 * @return WebElement
	 */
	public function getWrappedElement()
	{
		return $this->_wrappedElement;
	}

	/**
	 * Returns element name.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

}
