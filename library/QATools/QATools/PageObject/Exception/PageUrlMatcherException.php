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
 * Exception related to page url matchers.
 */
class PageUrlMatcherException extends PageFactoryException
{

	const TYPE_DUPLICATE_PRIORITY = 101;
	const TYPE_INVALID_ANNOTATION = 102;
}
