<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\Live\PageObject;


use tests\QATools\QATools\Live\AbstractLiveTestCase;
use tests\QATools\QATools\Live\PageObject\Pages\WebElementPage;

class PageTest extends AbstractLiveTestCase
{

	protected function setUp()
	{
		$this->pageFactoryClass = '\\QATools\\QATools\\PageObject\\PageFactory';
		parent::setUp();
	}

	public function testButton1LocatedById()
	{
		/** @var WebElementPage $page */
		$page = new WebElementPage($this->pageFactory);

		$page->button->click();

		$this->assertPageContains('clicked on [button1] button');
	}

	public function testCheckboxLocatedByCss()
	{
		/** @var WebElementPage $page */
		$page = new WebElementPage($this->pageFactory);

		$this->assertFalse($page->checkbox->isChecked());

		$page->checkbox->check();
		$this->assertTrue($page->checkbox->isChecked());
		$this->assertPageContains('change on [checkbox-without-label]; checked = yes');

		$page->checkbox->uncheck();
		$this->assertFalse($page->checkbox->isChecked());
		$this->assertPageContains('change on [checkbox-without-label]; checked = no');
	}

	public function testRadioGroupLocatedByName()
	{
		/** @var WebElementPage $page */
		$page = new WebElementPage($this->pageFactory);

		$this->assertFalse($page->radioGroup->isChecked());
		$this->assertCount(4, $page->radioGroup);

		$page->radioGroup->selectOption('1');

		$this->assertTrue($page->radioGroup->isSelected());

		$count = 0;

		foreach ( $page->radioGroup as $index => $radio ) {
			$count++;
			$radio->click();

			$this->assertEquals($page->radioGroup[$index], $radio);
			$this->assertEquals($index + 1, $radio->getValue());
		}

		$this->assertEquals(4, $count);
	}

	public function testTextInputsLocatedByCss()
	{
		/** @var WebElementPage $page */
		$page = new WebElementPage($this->pageFactory);

		$this->assertCount(3, $page->textInputs);

		$page->textInputs->setValue('text');

		$this->assertEquals('text', $page->textInputs[0]->getValue());
		$this->assertNotEquals('text', $page->textInputs[1]->getValue());
		$this->assertNotEquals('text', $page->textInputs[2]->getValue());

		$count = 0;

		foreach ( $page->textInputs as $index => $input ) {
			$count++;
			$input->setValue('text' . $index);
		}

		$this->assertEquals(3, $count);

		$this->assertEquals('text0', $page->textInputs[0]->getValue());
		$this->assertEquals('text1', $page->textInputs[1]->getValue());
		$this->assertEquals('text2', $page->textInputs[2]->getValue());
	}

}
