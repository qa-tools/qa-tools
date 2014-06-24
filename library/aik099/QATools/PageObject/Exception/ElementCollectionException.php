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
 * Exception related to ElementCollection.
 */
class ElementCollectionException extends ElementException
{
	const TYPE_ELEMENT_CLASS_MISSING = 201;

	const TYPE_INCORRECT_ELEMENT_CLASS = 202;

	const TYPE_ELEMENT_CLASS_MISMATCH = 203;

}
