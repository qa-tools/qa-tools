<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\Annotation;


use aik099\QATools\PageObject\Exception\AnnotationException;
use mindplay\annotations\Annotation;
use aik099\QATools\PageObject\How;

/**
 * Annotation for describing element selector in Selenium style.
 *
 * @link http://bit.ly/1angG8J
 * @usage('class'=>true, 'property'=>true, 'inherited'=>true)
 */
class FindByAnnotation extends Annotation
{

	/**
	 * CSS class name.
	 *
	 * @var string
	 */
	public $className;

	/**
	 * CSS selector.
	 *
	 * @var string
	 */
	public $css;

	/**
	 * ID attribute.
	 *
	 * @var string
	 */
	public $id;

	/**
	 * Link text.
	 *
	 * @var string
	 */
	public $linkText;

	/**
	 * NAME attribute.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Partial link text.
	 *
	 * @var string
	 */
	public $partialLinkText;

	/**
	 * HTML tag name.
	 *
	 * @var string
	 */
	public $tagName;

	/**
	 * Xpath.
	 *
	 * @var string
	 */
	public $xpath;

	/**
	 * How class constant.
	 *
	 * @var string
	 * @see How
	 */
	public $how;

	/**
	 * Using value.
	 *
	 * Used in combination with How.
	 *
	 * @var string
	 */
	public $using;

	/**
	 * Returns a selector, created based on annotation parameters.
	 *
	 * @return array
	 * @throws AnnotationException When selector from annotation is incorrectly specified.
	 */
	public function getSelector()
	{
		$direct_settings = array('className', 'css', 'id', 'linkText', 'name', 'partialLinkText', 'tagName', 'xpath');

		foreach ( $direct_settings as $direct_setting ) {
			if ( $this->$direct_setting ) {
				return array($direct_setting => $this->$direct_setting);
			}
		}

		if ( $this->how && $this->using ) {
			if ( !in_array($this->how, $direct_settings) ) {
				throw new AnnotationException(
					"FindBy annotation expects 'how' to be one of \\aik099\\QATools\\PageObject\\How class constants",
					AnnotationException::TYPE_INCORRECT_USAGE
				);
			}

			return array($this->how => $this->using);
		}

		throw new AnnotationException(
			sprintf(
				"FindBy annotation requires one of '%s' or both 'how' and 'using' parameters specified",
				implode("', '", $direct_settings)
			),
			AnnotationException::TYPE_INCORRECT_USAGE
		);
	}

}
