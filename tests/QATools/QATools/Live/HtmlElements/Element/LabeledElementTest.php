<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\Live\HtmlElements\Element;


use QATools\QATools\HtmlElements\Element\LabeledElement;

class LabeledElementTest extends TypifiedElementTestCase
{

	protected function setUp()
	{
		parent::setUp();

		$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\LabeledElement';
	}

	/**
	 * @dataProvider labelDataProvider
	 */
	public function testGetLabel($id, $label_text)
	{
		/** @var LabeledElement $label_element */
		$label_element = $this->createElement(array('id' => $id));
		$label = $label_element->getLabel();

		if ( is_null($label_text) ) {
			$this->assertNull($label, '->getLabel() haven\'t found label on a page');
		}
		else {
			$this->assertNotNull($label, '->getLabel() found label on a page');
			$this->assertEquals($label_text, $label->getText());
		}
	}

	/**
	 * @dataProvider labelDataProvider
	 */
	public function testGetLabelText($id, $label_text)
	{
		/** @var LabeledElement $label_element */
		$label_element = $this->createElement(array('id' => $id));
		$this->assertSame($label_text, $label_element->getLabelText());
	}

	/**
	 * @dataProvider labelDataProvider
	 */
	public function testGetText($id, $label_text)
	{
		/** @var LabeledElement $label_element */
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
			array('checkbox-with-following-label', 'label text 2'),
			array('checkbox-with-preceding-label', 'label text 3'),
			array('checkbox-inside-label', 'label text 4'),
		);
	}

}
