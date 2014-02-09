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


use Behat\Mink\Element\NodeElement;
use aik099\QATools\BEM\Element\Block;
use aik099\QATools\BEM\Element\IBlock;
use aik099\QATools\BEM\Exception\BEMPageFactoryException;
use aik099\QATools\PageObject\ElementLocator\IElementLocator;
use aik099\QATools\PageObject\IPageFactory;

/**
 * Class for lazy-proxy creation to ensure, that BEM elements are really accessed only at moment, when user needs them.
 *
 * @method \Mockery\Expectation shouldReceive
 *
 * @link http://bit.ly/14TbcR9
 */
class BlockProxy implements IBlock
{

	/**
	 * Block class name.
	 *
	 * @var string
	 */
	protected $className;

	/**
	 * Block.
	 *
	 * @var IBlock
	 */
	protected $object;

	/**
	 * Locator.
	 *
	 * @var IElementLocator
	 */
	protected $locator;

	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Page factory.
	 *
	 * @var IPageFactory
	 */
	protected $pageFactory;

	/**
	 * Initializes proxy for BEM block.
	 *
	 * @param string          $name         Block name.
	 * @param IElementLocator $locator      Selector.
	 * @param string          $class_name   Class name to proxy.
	 * @param IPageFactory    $page_factory Page factory.
	 */
	public function __construct($name, IElementLocator $locator, $class_name, IPageFactory $page_factory)
	{
		$this->name = $name;
		$this->className = $class_name;
		$this->locator = $locator;
		$this->pageFactory = $page_factory;
	}

	/**
	 * Returns block instance.
	 *
	 * @return Block
	 */
	protected function getObject()
	{
		if ( !is_object($this->object) ) {
			$nodes = $this->locator->findAll();

			$this->object = new $this->className($this->name, $nodes, $this->pageFactory);
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
	 * Returns name of the entity.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
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
		return $this->getObject()->findAll($selector, $locator);
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
		return $this->getObject()->find($selector, $locator);
	}

	/**
	 * Proxies all methods to sub-object.
	 *
	 * @param string $method    Method to proxy.
	 * @param array  $arguments Method arguments.
	 *
	 * @return mixed
	 * @throws BEMPageFactoryException When sub-object doesn't have a specified method.
	 */
	public function __call($method, array $arguments)
	{
		$block = $this->getObject();

		if ( !method_exists($block, $method) ) {
			$message = sprintf('"%s" method is not available on the %s', $method, get_class($block));

			throw new BEMPageFactoryException($message);
		}

		return call_user_func_array(array($block, $method), $arguments);
	}

}
