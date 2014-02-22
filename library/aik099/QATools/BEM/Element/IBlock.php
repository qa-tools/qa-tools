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


use Behat\Mink\Element\NodeElement;
use aik099\QATools\PageObject\ISearchContext;

/**
 * Interface, that every BEM block must implement.
 */
interface IBlock extends IPart, ISearchContext
{

	/**
	 * Returns block nodes.
	 *
	 * @return NodeElement[]
	 */
	public function getNodes();

	/**
	 * Returns first block element.
	 *
	 * @param string $element_name      Element name.
	 * @param string $modificator_name  Modificator name.
	 * @param string $modificator_value Modificator value.
	 *
	 * @return NodeElement[]|null
	 */
	public function getElement($element_name, $modificator_name = null, $modificator_value = null);

	/**
	 * Returns all block elements.
	 *
	 * @param string $element_name      Element name.
	 * @param string $modificator_name  Modificator name.
	 * @param string $modificator_value Modificator value.
	 *
	 * @return NodeElement[]
	 */
	public function getElements($element_name, $modificator_name = null, $modificator_value = null);

}
