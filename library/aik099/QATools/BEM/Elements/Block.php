<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM\Elements;


use Behat\Mink\Element\NodeElement;
use aik099\QATools\BEM\BEMPageFactory;
use aik099\QATools\PageObject\How;

/**
 * All BEM block classes must be descendants of this class.
 *
 * @method \Mockery\Expectation shouldReceive
 */
abstract class Block implements IBlock
{

	/**
	 * Name of the block.
	 *
	 * @var string
	 */
	private $_name;

	/**
	 * Elements, associated with this block.
	 *
	 * @var NodeElement[]
	 */
	private $_nodes;

	/**
	 * Create instance of BEM block.
	 *
	 * @param string              $name         Block name.
	 * @param array|NodeElement[] $nodes        Nodes associated with this block.
	 * @param BEMPageFactory      $page_factory Page factory.
	 */
	public final function __construct($name, array $nodes, BEMPageFactory $page_factory)
	{
		$this->_name = $name;
		$this->_nodes = $nodes;

		$page_factory->initElements($this, $page_factory->createDecorator($this));
	}

	/**
	 * Returns block nodes.
	 *
	 * @return NodeElement[]
	 */
	public function getNodes()
	{
		return $this->_nodes;
	}

	/**
	 * Returns first block element.
	 *
	 * @param string $element_name      Element name.
	 * @param string $modificator_name  Modificator name.
	 * @param string $modificator_value Modificator value.
	 *
	 * @return NodeElement[]|null
	 */
	public function getElement($element_name, $modificator_name = null, $modificator_value = null)
	{
		$items = $this->getElements($element_name, $modificator_name, $modificator_value);

		return count($items) ? current($items) : null;
	}

	/**
	 * Returns all block elements.
	 *
	 * @param string $element_name      Element name.
	 * @param string $modificator_name  Modificator name.
	 * @param string $modificator_value Modificator value.
	 *
	 * @return NodeElement[]
	 */
	public function getElements($element_name, $modificator_name = null, $modificator_value = null)
	{
		$locator = $this->getName() . '' . $element_name;

		return $this->findAll(How::CLASS_NAME, $locator);
	}

	/**
	 * Returns name of the block.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * Finds all elements with specified selector.
	 *
	 * @param string $selector Selector engine name.
	 * @param string $locator  Selector locator.
	 *
	 * @return NodeElement[]
	 */
	public function findAll($selector, $locator)
	{
		$items = array();

		foreach ($this->_nodes as $node) {
			$items += $node->findAll($selector, $locator);
		}

		return $items;
	}

	/**
	 * Finds first element with specified selector.
	 *
	 * @param string $selector Selector engine name.
	 * @param string $locator  Selector locator.
	 *
	 * @return NodeElement|null
	 */
	public function find($selector, $locator)
	{
		$items = $this->findAll($selector, $locator);

		return count($items) ? current($items) : null;
	}

}
