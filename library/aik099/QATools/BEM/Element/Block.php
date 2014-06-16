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


use aik099\QATools\BEM\ElementLocator\BEMElementLocator;
use aik099\QATools\PageObject\IPageFactory;
use Behat\Mink\Element\NodeElement;

/**
 * All BEM block classes must be descendants of this class.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class Block extends AbstractPart implements IBlock
{

	/**
	 * Elements, associated with this block.
	 *
	 * @var NodeElement[]
	 */
	private $_nodes;

	/**
	 * Locator.
	 *
	 * @var BEMElementLocator
	 */
	private $_locator;

	/**
	 * Create instance of BEM block.
	 *
	 * @param string              $name         Block name.
	 * @param array|NodeElement[] $nodes        Nodes associated with this block.
	 * @param IPageFactory        $page_factory Page factory.
	 * @param BEMElementLocator   $locator      Locator.
	 */
	public final function __construct($name, array $nodes, IPageFactory $page_factory, BEMElementLocator $locator)
	{
		parent::__construct($name);

		$this->_nodes = $nodes;
		$this->_locator = $locator;

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
	 * @return NodeElement|null
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
		$locator = $this->_locator->getElementLocator(
			$element_name,
			$this->getName(),
			$modificator_name,
			$modificator_value
		);

		return $this->findAll('se', $locator);
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

		foreach ( $this->_nodes as $node ) {
			$items = array_merge($items, $node->findAll($selector, $locator));
		}

		return $items;
	}

	/**
	 * Waits for an element(-s) to appear and returns it.
	 *
	 * @param integer  $timeout  Maximal allowed waiting time in milliseconds.
	 * @param callable $callback Callback, which result is both used as waiting condition and returned.
	 *                           Will receive reference to `this element` as first argument.
	 *
	 * @return mixed
	 * @throws \LogicException Always.
	 */
	public function waitFor($timeout, $callback)
	{
		throw new \LogicException('Waiting for elements not supported by the BEM methodology');
	}

}
