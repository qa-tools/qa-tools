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


use mindplay\annotations\Annotation;

/**
 * Defines page metadata.
 *
 * @usage('class'=>true, 'inherited'=>true)
 */
class PageUrlAnnotation extends Annotation
{

	/**
	 * Url to a page.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * GET params.
	 *
	 * @var array
	 */
	public $params = array();

	/**
	 * Forces secure HTTPS or HTTP.
	 *
	 * @var mixed
	 */
	public $secure = null;

	/**
	 * Initialize the annotation.
	 *
	 * @param array $properties Annotation parameters.
	 *
	 * @return void
	 */
	public function initAnnotation(array $properties)
	{
		$this->map($properties, array('url', 'params', 'secure'));

		parent::initAnnotation($properties);
	}

}
