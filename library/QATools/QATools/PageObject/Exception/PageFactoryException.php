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
 * Exception related to Page Factory.
 */
class PageFactoryException extends \Exception
{
	const TYPE_UNKNOWN_CLASS = 1;
	const TYPE_PAGE_MISSING_PREFIXES = 2;
	const TYPE_PAGE_NAME_MISSING = 3;
	const TYPE_PAGE_CLASS_NOT_FOUND = 4;

}
