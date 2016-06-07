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
 * Exception related to Form.
 */
class FormException extends TypifiedElementException
{

	const TYPE_UNKNOWN_FIELD = 221;

	const TYPE_READONLY_FIELD = 222;
}
