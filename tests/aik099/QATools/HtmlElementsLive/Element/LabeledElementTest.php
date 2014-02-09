<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\HtmlElementsLive\Element;


use aik099\QATools\HtmlElements\Element\LabeledElement;

class LabeledElementTest extends TypifiedElementTestCase
{

	/**
	 * Prepares test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\LabeledElement';
	}

	/**
	 * Test description.
	 *
	 * @param string $id         Element ID.
	 * @param string $label_text Label text.
	 *
	 * @return void
	 * @dataProvider labelDataProvider
	 */
	public function testGetLabel($id, $label_text)
	{
		/* @var $label_element LabeledElement */
		$label_element = $this->createElement(array('id' => $id));
		$label = $label_element->getLabel();

		if ( is_null($label_text) ) {
			$this->assertNull($label);
		}
		else {
			$this->assertEquals($label_text, $label->getText());
		}
	}

	/**
	 * Test description.
	 *
	 * @param string $id         Element ID.
	 * @param string $label_text Label text.
	 *
	 * @return void
	 * @dataProvider labelDataProvider
	 */
	public function testGetLabelText($id, $label_text)
	{
		/* @var $label_element LabeledElement */
		$label_element = $this->createElement(array('id' => $id));
		$this->assertSame($label_text, $label_element->getLabelText());
	}

	/**
	 * Test description.
	 *
	 * @param string $id         Element ID.
	 * @param string $label_text Label text.
	 *
	 * @return void
	 * @dataProvider labelDataProvider
	 */
	public function testGetText($id, $label_text)
	{
		/* @var $label_element LabeledElement */
		$label_element = $this->createElement(array('id' => $id));
		$this->assertSame($label_text, $label_element->getText());
	}

	/**
	 * Provides test data for label detection.
	 *
	 * @return array
	 */
	public function labelDataProvider()
	{
		return array(
			array('checkbox-without-label', null),
			array('checkbox-with-label', 'label text 2'),
			array('checkbox-inside-label', 'label text 3'),
		);
	}

}
