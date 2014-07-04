<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\BEM\Proxy;


use QATools\QATools\BEM\ElementLocator\BEMElementLocator;
use QATools\QATools\PageObject\Exception\ElementNotFoundException;
use Behat\Mink\Element\NodeElement;
use QATools\QATools\BEM\Element\IBlock;
use QATools\QATools\PageObject\IPageFactory;

/**
 * Class for lazy-proxy creation to ensure, that BEM elements are really accessed only at moment, when user needs them.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 *
 * @link http://bit.ly/14TbcR9
 */
class BlockProxy extends AbstractPartProxy implements IBlock
{

	/**
	 * Initializes BEM block proxy.
	 *
	 * @param string            $name         Block name.
	 * @param BEMElementLocator $locator      Locator.
	 * @param IPageFactory      $page_factory Page factory.
	 */
	public function __construct($name, BEMElementLocator $locator, IPageFactory $page_factory)
	{
		$this->className = '\\QATools\\QATools\\BEM\\Element\\Block';
		$this->elementClass = '\\QATools\\QATools\\BEM\\Element\\IBlock';

		parent::__construct($name, $locator, $page_factory);
	}

	/**
	 * Returns block instance.
	 *
	 * @return IBlock
	 * @throws ElementNotFoundException When block not found.
	 */
	public function getObject()
	{
		if ( !is_object($this->object) ) {
			$nodes = $this->locator->findAll();

			if ( !$nodes ) {
				throw new ElementNotFoundException('Block not found by selector: ' . (string)$this->locator);
			}

			$this->object = new $this->className($this->getName(), $nodes, $this->pageFactory, $this->locator);
			$this->object->setContainer($this->getContainer());
		}

		return $this->object;
	}

	/**
	 * Returns block nodes.
	 *
	 * @return NodeElement[]
	 */
	public function getNodes()
	{
		return $this->getObject()->getNodes();
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
		return $this->getObject()->getElement($element_name, $modificator_name, $modificator_value);
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
		return $this->getObject()->getElements($element_name, $modificator_name, $modificator_value);
	}

	/**
	 * Finds all elements with specified selector.
	 *
	 * @param string       $selector Selector engine name.
	 * @param string|array $locator  Selector locator.
	 *
	 * @return NodeElement[]
	 */
	public function findAll($selector, $locator)
	{
		return $this->getObject()->findAll($selector, $locator);
	}

	/**
	 * Finds first element with specified selector.
	 *
	 * @param string       $selector Selector engine name.
	 * @param string|array $locator  Selector locator.
	 *
	 * @return NodeElement|null
	 */
	public function find($selector, $locator)
	{
		return $this->getObject()->find($selector, $locator);
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
		return $this->getObject()->waitFor($timeout, $callback);
	}

}
