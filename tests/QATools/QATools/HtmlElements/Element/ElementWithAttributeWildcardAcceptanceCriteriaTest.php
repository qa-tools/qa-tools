<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\HtmlElements\Element;


class ElementWithAttributeWildcardAcceptanceCriteriaTest extends AbstractTypifiedElementTest
{

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\tests\\QATools\\QATools\\HtmlElements\\Fixture\\Element\\ElementWithAttributeWildcardAcceptanceCriteria';
		}

		$this->expectedTagName = 'button';
		$this->expectedAttributes = array('class' => 'test');

		parent::setUpTest();
	}

}
