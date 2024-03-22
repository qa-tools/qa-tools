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


use QATools\QATools\PageObject\Element\AbstractElementCollection;

/**
 * Represents a list of typified elements.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
abstract class AbstractTypifiedElementCollection extends AbstractElementCollection implements ITypifiedElement
{

	/**
	 * Name of the element.
	 *
	 * @var string
	 */
	private $_name;

	/**
	 * Initializes collection with a list of elements.
	 *
	 * @param array $elements Elements.
	 */
	public function __construct(array $elements = array())
	{
		if ( !$this->elementClass ) {
			$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\AbstractTypifiedElement';
		}

		parent::__construct($elements);
	}

	/**
	 * Sets a name of an element.
	 *
	 * This method is used by initialization mechanism and is not intended to be used directly.
	 *
	 * @param string $name Name to set.
	 *
	 * @return static
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

}
