<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM;


use aik099\QATools\BEM\Elements\Element;
use aik099\QATools\BEM\Elements\IElement;
use aik099\QATools\BEM\Exceptions\BEMPageFactoryException;
use aik099\QATools\PageObject\ElementLocators\IElementLocator;
use aik099\QATools\PageObject\Elements\IWebElement;
use aik099\QATools\PageObject\Elements\WebElement;

/**
 * Class for lazy-proxy creation to ensure, that BEM elements are really accessed only at moment, when user needs them
 *
 * @link http://bit.ly/14TbcR9
 *
 * @method \Mockery\Expectation shouldReceive
 */
class ElementProxy implements IElement
{

	/**
	 * Element class name.
	 *
	 * @var string
	 */
	protected $className;

	/**
	 * Element.
	 *
	 * @var Element
	 */
	protected $object;

	/**
	 * Selector.
	 *
	 * @var string
	 */
	protected $locator;

	/**
	 * Element name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Initializes proxy for BEM element.
	 *
	 * @param string          $name               Block name.
	 * @param IElementLocator $locator            Selector.
	 * @param string          $element_class_name Class name to proxy.
	 */
	public function __construct($name, IElementLocator $locator, $element_class_name)
	{
		$this->name = $name;
		$this->className = $element_class_name;
		$this->locator = $locator;
	}

	/**
	 * Returns element instance.
	 *
	 * @return Element
	 * @throws BEMPageFactoryException When element not found.
	 */
	protected function getObject()
	{
		if ( !is_object($this->object) ) {
			$node_element = $this->locator->find();

			if ( !$node_element ) {
				throw new BEMPageFactoryException('Element not found by selector: ' . (string)$this->locator);
			}

			$web_element = WebElement::fromNodeElement($node_element);

			$this->object = new $this->className($this->name, $web_element);
		}

		return $this->object;
	}

	/**
	 * Returns name of the entity.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Returns wrapped element.
	 *
	 * @return IWebElement
	 */
	public function getWrappedElement()
	{
		return $this->getObject()->getWrappedElement();
	}

}
