<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\Element;


use aik099\QATools\PageObject\ISearchContext;

/**
 * Represents a list of elements.
 *
 * @method \Mockery\Expectation shouldReceive
 */
class WebElementCollection extends AbstractElementCollection implements IWebElement
{

	/**
	 * Container, where element is located.
	 *
	 * @var ISearchContext
	 */
	protected $container;

	/**
	 * Initializes collection with a list of elements.
	 *
	 * @param array $elements Elements.
	 */
	public function __construct(array $elements = array())
	{
		if ( !$this->elementClass ) {
			$this->elementClass = '\\aik099\\QATools\\PageObject\\Element\\WebElement';
		}

		parent::__construct($elements);
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
	 * Returns page element.
	 *
	 * @return ISearchContext
	 */
	public function getContainer()
	{
		return $this->container;
	}

}
