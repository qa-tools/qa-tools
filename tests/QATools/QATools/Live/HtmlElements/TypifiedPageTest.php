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


use tests\QATools\QATools\Live\AbstractLiveTestCase;
use tests\QATools\QATools\Live\HtmlElements\Pages\TypifiedElementPage;

class TypifiedPageTest extends AbstractLiveTestCase
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
		$this->assertCount(1, $page->radioGroup);

		for ( $i = 1; $i <= 4; $i++ ) {
			$page->radioGroup->selectButtonByValue($i);
			$this->assertEquals($i, $page->radioGroup->getSelectedButton()->getValue());
		}
	}

}
