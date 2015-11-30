<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\HtmlElements\Fixture\Element;


use QATools\QATools\HtmlElements\Element\AbstractTypifiedElement;

class ElementWithTagWildcardAcceptanceCriteria extends AbstractTypifiedElement
{

	/**
	 * List of acceptance criteria.
	 *
	 * @var array
	 */
	protected $acceptanceCriteria = array(
		array('tag' => '*'),
	);

}
