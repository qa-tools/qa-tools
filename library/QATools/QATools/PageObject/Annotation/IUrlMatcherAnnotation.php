<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\Annotation;


/**
 * Interface for matching annotations.
 */
interface IUrlMatcherAnnotation
{

	/**
	 * Validates required data.
	 *
	 * @return boolean
	 */
	public function isValid();

}
