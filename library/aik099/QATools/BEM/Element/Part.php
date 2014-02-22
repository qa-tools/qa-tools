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


use aik099\QATools\PageObject\ISearchContext;

/**
 * Part of BEM.
 *
 * @method \Mockery\Expectation shouldReceive
 */
abstract class Part implements IPart
{

	/**
	 * Name of the BEM part.
	 *
	 * @var string
	 */
	private $_name;

	/**
	 * Container, where element is located.
	 *
	 * @var ISearchContext
	 */
	protected $container;

	/**
	 * Creates BEM part.
	 *
	 * @param string $name BEM part name.
	 */
	public function __construct($name)
	{
		$this->_name = $name;
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

	/**
	 * Sets container, where element is located.
	 *
	 * @param ISearchContext|null $container Element container.
	 *
	 * @return self
	 */
	public function setContainer(ISearchContext $container = null)
	{
		$this->container = $container;

		return $this;
	}

	/**
	 * Returns container, where element is located.
	 *
	 * @return ISearchContext
	 */
	public function getContainer()
	{
		return $this->container;
	}

}
