<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\HtmlElements\Exception;


use QATools\QATools\PageObject\Exception\ElementException;

/**
 * Exception related to Typified Element.
 */
class TypifiedElementException extends ElementException
{
	const TYPE_INCORRECT_WRAPPED_ELEMENT = 201;
}
