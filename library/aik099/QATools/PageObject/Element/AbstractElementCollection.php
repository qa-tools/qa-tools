<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace aik099\QATools\PageObject\Element;


use aik099\QATools\PageObject\Exception\ElementCollectionException;
use aik099\QATools\PageObject\IPageFactory;
use Behat\Mink\Element\NodeElement;

/**
 * Represents a list of elements.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
abstract class AbstractElementCollection implements \Iterator, \ArrayAccess, \Countable
{

	/**
	 * Currently active element.
	 *
	 * @var integer
	 */
	private $_position = 0;

	/**
	 * Elements in the collection.
	 *
	 * @var array
	 */
	private $_elements = array();

	/**
	 * Element class, that is allowed in the collection.
	 *
	 * @var string
	 */
	protected $elementClass = '';

	/**
	 * Initializes collection with a list of elements.
	 *
	 * @param array $elements Elements.
	 *
	 * @throws ElementCollectionException When collection has invalid collection class.
	 */
	public function __construct(array $elements = array())
	{
		$allowed_elements = array();

		if ( !$this->elementClass ) {
			throw new ElementCollectionException(
				'Collection element class is not set',
				ElementCollectionException::TYPE_ELEMENT_CLASS_MISSING
			);
		}

		foreach ( $elements as $element ) {
			if ( $this->assertElement($element) ) {
				$allowed_elements[] = $element;
			}
		}

		$this->_elements = $allowed_elements;
	}

	/**
	 * Determines if an element can be added to a collection.
	 *
	 * @param mixed $element Element.
	 *
	 * @return boolean
	 */
	protected function acceptElement($element)
	{
		return true;
	}

	/**
	 * Rewind the Iterator to the first element.
	 *
	 * @return void
	 */
	public function rewind()
	{
		$this->_position = 0;
	}

	/**
	 * Return the current element.
	 *
	 * @return mixed
	 */
	public function current()
	{
		return $this->_elements[$this->_position];
	}

	/**
	 * Return the key of the current element.
	 *
	 * @return integer
	 */
	public function key()
	{
		return $this->_position;
	}

	/**
	 * Move forward to next element.
	 *
	 * @return void
	 */
	public function next()
	{
		++$this->_position;
	}

	/**
	 * Checks if current position is valid.
	 *
	 * @return boolean
	 */
	public function valid()
	{
		return $this->offsetExists($this->_position);
	}

	/**
	 * Offset to set.
	 *
	 * @param integer $offset The offset to assign the value to.
	 * @param mixed   $value  The value to set.
	 *
	 * @return void
	 * @throws \InvalidArgumentException When invalid element given.
	 */
	public function offsetSet($offset, $value)
	{
		if ( !$this->assertElement($value) ) {
			return;
		}

		if ( is_null($offset) ) {
			$this->_elements[] = $value;
		}
		else {
			$this->_elements[$offset] = $value;
		}
	}

	/**
	 * Whether a offset exists.
	 *
	 * @param integer $offset An offset to check for.
	 *
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return isset($this->_elements[$offset]);
	}

	/**
	 * Offset to unset.
	 *
	 * @param integer $offset The offset to unset.
	 *
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->_elements[$offset]);
	}

	/**
	 * Offset to retrieve.
	 *
	 * @param integer $offset The offset to retrieve.
	 *
	 * @return mixed|null
	 */
	public function offsetGet($offset)
	{
		if ( isset($this->_elements[$offset]) ) {
			return $this->_elements[$offset];
		}

		return null;
	}

	/**
	 * Count elements of an object.
	 *
	 * @return integer
	 */
	public function count()
	{
		return count($this->_elements);
	}

	/**
	 * Creates new collection by wrapping given array of Node elements.
	 *
	 * @param array|NodeElement[] $node_elements Node elements to wrap.
	 * @param string              $element_class Class name to wrap Node elements with.
	 * @param IPageFactory        $page_factory  Page factory (optional) to use during wrapping.
	 *
	 * @return static
	 * @throws ElementCollectionException When element class used doesn't allow adding NodeElements inside.
	 */
	public static function fromNodeElements(array $node_elements, $element_class = null, IPageFactory $page_factory = null)
	{
		$collection = new static();

		if ( !isset($element_class) ) {
			$element_class = $collection->elementClass;
		}

		if ( !$collection->isNodeElementAware($element_class) ) {
			throw new ElementCollectionException(
				sprintf('Collection element class "%s" must implement INodeElementAware interface', $element_class),
				ElementCollectionException::TYPE_INCORRECT_ELEMENT_CLASS
			);
		}

		foreach ( $node_elements as $node_element ) {
			$collection[] = call_user_func(array($element_class, 'fromNodeElement'), $node_element, $page_factory);
		}

		return $collection;
	}

	/**
	 * Determines if class is NodeElement aware.
	 *
	 * @param string $class_name Class name.
	 *
	 * @return boolean
	 */
	protected function isNodeElementAware($class_name)
	{
		$node_element_aware_interface = 'aik099\\QATools\\PageObject\\Element\\INodeElementAware';

		if ( class_exists($class_name) ) {
			return in_array($node_element_aware_interface, class_implements($class_name));
		}

		return false;
	}

	/**
	 * Checks that element's class is allowed in collection.
	 *
	 * @param mixed $element Element.
	 *
	 * @return boolean
	 * @throws ElementCollectionException When class of given element doesn't match one, that collection accepts.
	 */
	protected function assertElement($element)
	{
		if ( !is_a($element, $this->elementClass) ) {
			$message = 'Collection element must be of "%s" class, but element of "%s" class given';

			throw new ElementCollectionException(
				sprintf($message, $this->elementClass, get_class($element)),
				ElementCollectionException::TYPE_ELEMENT_CLASS_MISMATCH
			);
		}

		return $this->acceptElement($element);
	}

}
