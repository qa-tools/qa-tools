<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\PageObject\Element;


use Mockery as m;
use aik099\QATools\PageObject\Element\WebElement;
use tests\aik099\QATools\PageObject\Element\AbstractElementCollectionTestCase;

class WebElementCollectionTest extends AbstractElementCollectionTestCase
{

	protected function setUp()
	{
		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\aik099\\QATools\\PageObject\\Element\\WebElementCollection';
			$this->collectionElementClass = '\\aik099\\QATools\\PageObject\\Element\\WebElement';
		}

		parent::setUp();
	}

	public function testSetContainer()
	{
		$container = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');

		$this->assertSame($this->element, $this->element->setContainer($container));
	}

	public function testGetContainer()
	{
		$container = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');
		$this->element->setContainer($container);

		$this->assertEquals($container, $this->element->getContainer());
	}

}
