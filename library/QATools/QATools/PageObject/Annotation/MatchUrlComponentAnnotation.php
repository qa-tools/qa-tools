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
 * Annotation describing the component URL matching.
 *
 * @usage('class'=>true, 'inherited'=>true, 'multiple'=>true)
 */
class MatchUrlComponentAnnotation extends Annotation implements IMatchUrlAnnotation
{

	/**
	 * Path.
	 *
	 * @var string|null
	 */
	public $path;

	/**
	 * Query params.
	 *
	 * @var array|null
	 */
	public $params;

	/**
	 * Protocol (http/https).
	 *
	 * @var boolean|null
	 */
	public $secure;

	/**
	 * Anchor.
	 *
	 * @var string|null
	 */
	public $anchor;

	/**
	 * Hostname.
	 *
	 * @var string|null
	 */
	public $host;

	/**
	 * Port.
	 *
	 * @var integer|null
	 */
	public $port;

	/**
	 * Username.
	 *
	 * @var string|null
	 */
	public $user;

	/**
	 * Password.
	 *
	 * @var string|null
	 */
	public $pass;

	/**
	 * Initialize the annotation.
	 *
	 * @param array $properties Annotation parameters.
	 *
	 * @return void
	 */
	public function initAnnotation(array $properties)
	{
		$this->map($properties, array('path', 'params', 'anchor', 'secure', 'host', 'port', 'user', 'pass'));

		parent::initAnnotation($properties);
	}

	/**
	 * Validates required data.
	 *
	 * @return boolean
	 */
	public function isValid()
	{
		return isset($this->path)
			|| isset($this->params)
			|| isset($this->anchor)
			|| isset($this->secure)
			|| isset($this->host)
			|| isset($this->port)
			|| isset($this->user)
			|| isset($this->pass);
	}

}
