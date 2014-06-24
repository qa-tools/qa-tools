<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace aik099\QATools\HtmlElements\Element;


/**
 * Represents a named entity. Used to operate with names of blocks and typified elements.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
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
