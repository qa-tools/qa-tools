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


class ElementWithTagWildcardAcceptanceCriteriaTest extends AbstractTypifiedElementTest
{

	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\tests\\QATools\\QATools\\HtmlElements\\Fixture\\Element\\ElementWithTagWildcardAcceptanceCriteria';
		}

		$this->expectedTagName = 'button';

		parent::setUp();
	}

}
