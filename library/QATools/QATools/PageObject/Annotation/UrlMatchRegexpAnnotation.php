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


use mindplay\annotations\Annotation;

/**
 * Annotation describing the regular expression URL match.
 *
 * @usage('class'=>true, 'inherited'=>true, 'multiple'=>true)
 */
class UrlMatchRegexpAnnotation extends Annotation implements IUrlMatcherAnnotation
{

	/**
	 * Regexp match for the url.
	 *
	 * @var string
	 */
	public $regexp;

	/**
	 * Initialize the annotation.
	 *
	 * @param array $properties Annotation parameters.
	 *
	 * @return void
	 */
	public function initAnnotation(array $properties)
	{
		$this->map($properties, array('regexp'));

		parent::initAnnotation($properties);
	}

	/**
	 * Validates required data.
	 *
	 * @return boolean
	 */
	public function isValid()
	{
		return !empty($this->regexp);
	}

}
