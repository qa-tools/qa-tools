<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\HtmlElements\Element;


/**
 * Represents a named entity. Used to operate with names of blocks and typified elements.
 *
 * @method \Mockery\Expectation shouldReceive()
 */
interface INamed
{

	/**
	 * Returns name of the entity.
	 *
	 * @return string
	 */
	public function getName();

}
