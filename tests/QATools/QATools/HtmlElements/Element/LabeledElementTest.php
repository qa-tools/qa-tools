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


use Mockery as m;
use QATools\QATools\HtmlElements\Element\LabeledElement;

class LabeledElementTest extends AbstractTypifiedElementTest
{

	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\LabeledElement';
		}

		parent::setUp();
	}

	public function testGetLabelById()
	{
		$container = m::mock('\\QATools\\QATools\\PageObject\\ISearchContext');
		$container
			->shouldReceive('find')
			->with('xpath', 'descendant-or-self::label[@for = ID_VALUE_ESCAPED]')
			->once()
			->andReturn('FOUND1');

		$this->webElement->shouldReceive('getContainer')->withNoArgs()->once()->andReturn($container);
		$this->webElement->shouldReceive('getAttribute')->with('id')->once()->andReturn('ID_VALUE');

		$this->selectorsHandler->shouldReceive('xpathLiteral')->with('ID_VALUE')->andReturn('ID_VALUE_ESCAPED');

		$this->assertEquals('FOUND1', $this->getElement()->getLabel());
	}

	public function testGetParentLabel()
	{
		$this->webElement->shouldReceive('getAttribute')->with('id')->once();
		$this->webElement->shouldReceive('find')->with('xpath', 'parent::label')->once()->andReturn('FOUND2');

		$this->assertEquals('FOUND2', $this->getElement()->getLabel());
	}

	public function testGetFollowingLabel()
	{
		$this->webElement
			->shouldReceive('getAttribute')
			->with('id')
			->once();
		$this->webElement
			->shouldReceive('find')
			->with('xpath', 'parent::label')
			->once();
		$this->webElement
			->shouldReceive('find')
			->with('xpath', 'following-sibling::*[1][self::label]')
			->once()
			->andReturn('FOUND3');

		$this->assertEquals('FOUND3', $this->getElement()->getLabel());
	}

	public function testGetLabelTextNotFound()
	{
		/* @var $element LabeledElement */
		$element = $this->mockElement(array('getLabel'));
		$element->shouldReceive('getLabel')->once();

		$this->assertNull($element->getLabelText());
	}

	public function testGetLabelTextFound()
	{
		$expected = 'LABEL_TEXT';
		$label = m::mock('\\Behat\\Mink\\Element\\NodeElement');
		$label->shouldReceive('getText')->once()->andReturn($expected);

		/* @var $element LabeledElement */
		$element = $this->mockElement(array('getLabel'));
		$element->shouldReceive('getLabel')->andReturn($label);

		$this->assertEquals($expected, $element->getLabelText());
	}

	public function testGetText()
	{
		$expected = 'OK';

		/* @var $element LabeledElement */
		$element = $this->mockElement(array('getLabelText'));
		$element->shouldReceive('getLabelText')->once()->andReturn($expected);

		$this->assertEquals($expected, $element->getText());
	}

	/**
	 * Returns existing element.
	 *
	 * @return LabeledElement
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

}
