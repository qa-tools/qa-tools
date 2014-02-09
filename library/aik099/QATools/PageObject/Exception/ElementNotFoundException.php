<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\Exception;


/**
 * Exception related to not found element.
 */
class ElementNotFoundException extends ElementException
{

	/**
	 * Creates exception instance.
	 *
	 * @param string     $message  Message.
	 * @param integer    $code     Code.
	 * @param \Exception $previous Previous exception.
	 */
	public function __construct($message = "", $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, self::TYPE_NOT_FOUND, $previous);
	}

}
