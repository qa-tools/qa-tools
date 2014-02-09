<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\HtmlElements\Element;


use aik099\QATools\HtmlElements\Exception\TypifiedElementException;


/**
 * Represents web page file upload element.
 */
class FileInput extends TypifiedElement implements ISimpleSetter
{

	/**
	 * Indicates whether this select element support selecting multiple options at the same time.
	 *
	 * @return boolean
	 */
	public function isMultiple()
	{
		return $this->hasAttribute('multiple');
	}

	/**
	 * Sets a file to be uploaded.
	 *
	 * @param string $filename Filename.
	 *
	 * @return self
	 * @throws TypifiedElementException When file could not be found on disk.
	 */
	public function setFileToUpload($filename)
	{
		if ( !file_exists($filename) ) {
			throw new TypifiedElementException('File "' . $filename . '" doesn\'t exist');
		}

		$this->getWrappedElement()->attachFile($filename);

		return $this;
	}

	/**
	 * Sets value to the element.
	 *
	 * @param mixed $value New value.
	 *
	 * @return self
	 */
	public function setValue($value)
	{
		return $this->setFileToUpload($value);
	}

}
