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


/**
 * Exception related to Select.
 */
class SelectException extends TypifiedElementException
{

	const TYPE_NOT_SELECTED = 241;

	const TYPE_NOT_MULTISELECT = 242;

	const TYPE_NOT_SUPPORTED = 243;

	const TYPE_UNBOUND_OPTION = 244;
}
