<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace aik099\QATools\PageObject\Exception;


/**
 * Exception related to annotations.
 */
class AnnotationException extends PageFactoryException
{
	const TYPE_REQUIRED = 101;

	const TYPE_INCORRECT_USAGE = 102;

}
