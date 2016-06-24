<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\Element;


use QATools\QATools\PageObject\Exception\ElementCollectionException;
use QATools\QATools\PageObject\IPageFactory;
use Behat\Mink\Element\NodeElement;

/**
 * Represents a list of elements.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
abstract class AbstractElementCollection extends \ArrayObject
{

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

		parent::__construct($allowed_elements);
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
	 * Offset to set.
	 *
	 * @param mixed $index  The offset to assign the value to.
	 * @param mixed $newval The value to set.
	 *
	 * @return void
	 */
	public function offsetSet($index, $newval)
	{
		if ( !$this->assertElement($newval) ) {
			return;
		}

		parent::offsetSet($index, $newval);
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
	public static function fromNodeElements(
		array $node_elements,
		$element_class = null,
		IPageFactory $page_factory
	) {
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
		$node_element_aware_interface = 'QATools\\QATools\\PageObject\\Element\\INodeElementAware';

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
