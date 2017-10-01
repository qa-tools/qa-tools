<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Element;


use Mockery as m;

class WebElementCollectionTest extends AbstractElementCollectionTestCase
{

	protected function setUp()
	{
		if ( $this->collectionClass === null ) {
			$this->collectionClass = '\\QATools\\QATools\\PageObject\\Element\\WebElementCollection';
			$this->collectionElementClass = '\\QATools\\QATools\\PageObject\\Element\\WebElement';
		}

		parent::setUp();
	}

}
