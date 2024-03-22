<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\HtmlElements\Element;


use QATools\QATools\HtmlElements\Exception\FileInputException;

/**
 * Represents web page file upload element.
 */
class FileInput extends AbstractTypifiedElement implements ISimpleSetter
{

	/**
	 * List of acceptance criteria.
	 *
	 * @var array
	 */
	protected $acceptanceCriteria = array(
		array('tag' => 'input', 'attrs' => array('type' => 'file')),
	);

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
	 * @return static
	 * @throws FileInputException When file could not be found on disk.
	 */
	public function setFileToUpload($filename)
	{
		if ( !file_exists($filename) ) {
			throw new FileInputException(
				'File "' . $filename . '" doesn\'t exist',
				FileInputException::TYPE_FILE_NOT_FOUND
			);
		}

		$this->getWrappedElement()->attachFile($filename);

		return $this;
	}

	/**
	 * Sets value to the element.
	 *
	 * @param mixed $value New value.
	 *
	 * @return static
	 */
	public function setValue($value)
	{
		return $this->setFileToUpload($value);
	}

}
