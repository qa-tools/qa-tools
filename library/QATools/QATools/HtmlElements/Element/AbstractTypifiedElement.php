<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\HtmlElements\Element;


use Behat\Mink\Element\NodeElement;
use Behat\Mink\Selector\SelectorsHandler;
use Behat\Mink\Session;
use QATools\QATools\HtmlElements\Exception\TypifiedElementException;
use QATools\QATools\PageObject\Element\INodeElementAware;
use QATools\QATools\PageObject\Element\WebElement;
use QATools\QATools\PageObject\IPageFactory;

/**
 * The base class to be used for making classes representing typified elements (i.e web page controls such as
 * text inputs, buttons or more complex elements).
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
abstract class AbstractTypifiedElement implements ITypifiedElement, INodeElementAware
{
	const CRITERION_TAG = 'tag';

	const CRITERION_ATTRS = 'attrs';

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
	 * List of acceptance criteria.
	 *
	 * @var array
	 */
	protected $acceptanceCriteria = array();

	/**
	 * Specifies wrapped WebElement.
	 *
	 * @param WebElement $wrapped_element Element to be wrapped.
	 */
	public function __construct(WebElement $wrapped_element)
	{
		$this->_wrappedElement = $wrapped_element;

		$this->assertWrappedElement();
	}

	/**
	 * Creates Element instance based on existing NodeElement instance.
	 *
	 * @param NodeElement  $node_element Node element.
	 * @param IPageFactory $page_factory Page factory.
	 *
	 * @return static
	 */
	public static function fromNodeElement(NodeElement $node_element, IPageFactory $page_factory = null)
	{
		$wrapped_element = WebElement::fromNodeElement($node_element);

		return new static($wrapped_element);
	}

	/**
	 * Checks that wrapped element meets the acceptance criteria.
	 *
	 * @return void
	 * @throws TypifiedElementException When no criteria matches.
	 */
	protected function assertWrappedElement()
	{
		if ( !$this->acceptanceCriteria ) {
			return;
		}

		foreach ( $this->acceptanceCriteria as $criterion ) {
			if ( !$this->isTagNameMatching($criterion) ) {
				continue;
			}

			if ( $this->isAttributeMatching($criterion) ) {
				return;
			}
		}

		$message = 'Wrapped element "%s" does not match "%s" criteria';

		throw new TypifiedElementException(
			sprintf($message, $this->getWrappedElement(), get_class($this)),
			TypifiedElementException::TYPE_INCORRECT_WRAPPED_ELEMENT
		);
	}

	/**
	 * Checks if the tag name(s) of the criterion are matching.
	 *
	 * @param array $criterion The criterion.
	 *
	 * @return boolean
	 */
	protected function isTagNameMatching(array $criterion)
	{
		if ( !isset($criterion[self::CRITERION_TAG]) ) {
			return true;
		}

		return $this->isValueMatchingCriterionDefinition($this->getTagName(), $criterion[self::CRITERION_TAG]);
	}

	/**
	 * Checks if the attributes of the criterion are matching.
	 *
	 * @param array $criterion The criterion.
	 *
	 * @return boolean
	 */
	protected function isAttributeMatching(array $criterion)
	{
		if ( !isset($criterion[self::CRITERION_ATTRS]) ) {
			return true;
		}

		foreach ( $criterion[self::CRITERION_ATTRS] as $attribute => $definition ) {
			if ( $this->isValueMatchingCriterionDefinition($this->getAttribute($attribute), $definition) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks if passed value matching the defined criterion.
	 *
	 * @param string $value     Value to match.
	 * @param string $criterion The criterion.
	 *
	 * @return boolean
	 */
	protected function isValueMatchingCriterionDefinition($value, $criterion)
	{
		return preg_match('/^(' . str_replace('*', '.*', $criterion) . ')$/', $value);
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
	 * Sets a name of an element.
	 *
	 * This method is used by initialization mechanism and is not intended to be used directly.
	 *
	 * @param string $name Name to set.
	 *
	 * @return self
	 */
	public function setName($name)
	{
		$this->_name = $name;

		return $this;
	}

	/**
	 * Returns name of the entity.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * Returns wrapped element session.
	 *
	 * @return Session
	 */
	public function getSession()
	{
		return $this->getWrappedElement()->getSession();
	}

	/**
	 * Checks whether current node is visible on page.
	 *
	 * @return boolean
	 */
	public function isVisible()
	{
		return $this->getWrappedElement()->isVisible();
	}

	/**
	 * Checks if an element is still valid.
	 *
	 * @return boolean
	 */
	public function isValid()
	{
		return $this->getWrappedElement()->isValid();
	}

	/**
	 * Checks whether element has attribute with specified name.
	 *
	 * @param string $name Attribute name.
	 *
	 * @return boolean
	 */
	public function hasAttribute($name)
	{
		return $this->getWrappedElement()->hasAttribute($name);
	}

	/**
	 * Returns specified attribute value.
	 *
	 * @param string $name Attribute name.
	 *
	 * @return mixed|null
	 */
	public function getAttribute($name)
	{
		return $this->getWrappedElement()->getAttribute($name);
	}

	/**
	 * Returns XPath for handled element.
	 *
	 * @return string
	 */
	public function getXpath()
	{
		return $this->getWrappedElement()->getXpath();
	}

	/**
	 * Returns current node tag name.
	 *
	 * @return string
	 */
	public function getTagName()
	{
		return $this->getWrappedElement()->getTagName();
	}

	/**
	 * Returns selectors handler.
	 *
	 * @return SelectorsHandler
	 */
	protected function getSelectorsHandler()
	{
		return $this->getSession()->getSelectorsHandler();
	}

	/**
	 * Checks, that Selenium driver is used.
	 *
	 * @return boolean
	 */
	protected function isSeleniumDriver()
	{
		return is_a($this->getSession()->getDriver(), '\\Behat\\Mink\\Driver\\Selenium2Driver');
	}

	/**
	 * Returns string representation of element.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return 'element (class: ' . get_class($this) . '; xpath: ' . $this->getXpath() . ')';
	}

}
