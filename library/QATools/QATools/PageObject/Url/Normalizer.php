<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\Url;


use QATools\QATools\PageObject\Annotation\PageUrlAnnotation;

/**
 * Returns the normalized url components of a page.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class Normalizer
{

	/**
	 * The base url.
	 *
	 * @var string
	 */
	protected $baseUrl;

	/**
	 * Creates Normalizer instance.
	 *
	 * @param string $base_url The base url.
	 */
	public function __construct($base_url)
	{
		$this->baseUrl = $base_url;
	}

	/**
	 * Returns normalized url.
	 *
	 * @param PageUrlAnnotation $annotation The page url annotation.
	 *
	 * @return array
	 */
	public function normalize(PageUrlAnnotation $annotation)
	{
		$parser = new Parser($this->baseUrl);
		$parser->merge(new Parser($annotation->url));

		$parser->setParams(array_merge($parser->getParams(), $annotation->params));

		$ret = $parser->getComponents();

		if ( $annotation->secure !== null && !empty($ret['scheme']) ) {
			$ret['scheme'] = $annotation->secure ? 'https' : 'http';
		}

		return $ret;
	}

}
