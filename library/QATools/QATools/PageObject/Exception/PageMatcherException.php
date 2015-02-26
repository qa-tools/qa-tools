<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\Exception;


/**
 * Exception related to page matcher.
 */
class PageMatcherException extends PageFactoryException
{

	const TYPE_MISSING_ANNOTATION = 101;
	const TYPE_INCOMPLETE_ANNOTATION = 102;
}
