<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM\Exception;


use aik099\QATools\PageObject\Exception\ElementException as BaseElementException;

/**
 * Exception related to BEM elements/blocks.
 */
class ElementException extends BaseElementException
{

	const TYPE_BLOCK_REQUIRED = 251;

	const TYPE_ELEMENT_REQUIRED = 252;
}
