<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\Live\HtmlElements;


use QATools\QATools\PageObject\Page;
use QATools\QATools\PageObject\PageFactory;
use tests\QATools\QATools\Live\AbstractLivePageTestCase;
use tests\QATools\QATools\Live\HtmlElements\Pages\RelativePage;
use tests\QATools\QATools\Live\HtmlElements\Pages\TypifiedElementPage;

class TypifiedPageTest extends AbstractLivePageTestCase
{

	protected function setUp()
	{
		$this->pageFactoryClass = '\\QATools\\QATools\\HtmlElements\\TypifiedPageFactory';
		parent::setUp();
	}

	public function testButton1LocatedById()
	{
		/** @var TypifiedElementPage $page */
		$page = new TypifiedElementPage($this->pageFactory);

		$page->button->click();

		$this->assertPageContains('clicked on [button1] button');
	}

	public function testCheckboxLocatedByCss()
	{
		/** @var TypifiedElementPage $page  */
		$page = new TypifiedElementPage($this->pageFactory);

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
		/** @var TypifiedElementPage $page  */
		$page = new TypifiedElementPage($this->pageFactory);

		$this->assertFalse($page->radioGroup->hasSelectedButton(), 'No radio button is selected initially');
		$this->assertCount(4, $page->radioGroup);

		for ( $i = 1; $i <= 4; $i++ ) {
			$page->radioGroup->selectButtonByValue($i);
			$this->assertEquals($i, $page->radioGroup->getSelectedButton()->getValue());
		}

		$count = 0;

		foreach ( $page->radioGroup as $index => $radio ) {
			$count++;
			$radio->select();

			$this->assertEquals($page->radioGroup[$index], $radio);
			$this->assertEquals($index + 1, $page->radioGroup->getSelectedButton()->getValue());
		}

		$this->assertEquals(4, $count);
	}

	public function testTextInputsLocatedByCss()
	{
		/** @var TypifiedElementPage $page */
		$page = new TypifiedElementPage($this->pageFactory);

		$this->assertCount(3, $page->textInputs);

		$page->textInputs->setValue('text');

		$this->assertEquals('text', $page->textInputs[0]->getText());
		$this->assertNotEquals('text', $page->textInputs[1]->getText());
		$this->assertNotEquals('text', $page->textInputs[2]->getText());

		$count = 0;

		foreach ( $page->textInputs as $index => $input ) {
			$count++;
			$input->setValue('text' . $index);
		}

		$this->assertEquals(3, $count);

		$this->assertEquals('text0', $page->textInputs[0]->getText());
		$this->assertEquals('text1', $page->textInputs[1]->getText());
		$this->assertEquals('text2', $page->textInputs[2]->getText());

		foreach ( $page->textInputs as $input ) {
			$input->clear();
		}

		$this->assertEquals('', $page->textInputs[0]->getText());
		$this->assertEquals('', $page->textInputs[1]->getText());
		$this->assertEquals('', $page->textInputs[2]->getText());
	}

	/**
	 * Creates page with relative url.
	 *
	 * @param PageFactory $page_factory Page factory.
	 *
	 * @return Page
	 */
	protected function createRelativePage(PageFactory $page_factory)
	{
		return new RelativePage($page_factory);
	}

}
