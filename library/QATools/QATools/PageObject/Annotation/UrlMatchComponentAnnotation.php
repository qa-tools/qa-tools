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
 * Annotation describing the component URL match.
 *
 * @usage('class'=>true, 'inherited'=>true, 'multiple'=>true)
 */
class UrlMatchComponentAnnotation extends Annotation implements IUrlMatcherAnnotation
{

	/**
	 * Match for the path.
	 *
	 * @var string|null
	 */
	public $path;

	/**
	 * Match for query params.
	 *
	 * @var array|null
	 */
	public $params;

	/**
	 * Match for http/https.
	 *
	 * @var boolean|null
	 */
	public $secure;

	/**
	 * Match for anchor.
	 *
	 * @var string|null
	 */
	public $anchor;

	/**
	 * Match for the host.
	 *
	 * @var string|null
	 */
	public $host;

	/**
	 * Match for the port.
	 *
	 * @var int|null
	 */
	public $port;

	/**
	 * Match for the user.
	 *
	 * @var string|null
	 */
	public $user;

	/**
	 * Match for the password.
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
		return $this->secure !== null
			|| $this->path !== null
			|| $this->params !== null
			|| $this->anchor !== null
			|| $this->host !== null
			|| $this->port !== null
			|| $this->user !== null
			|| $this->pass !== null;
	}

}
