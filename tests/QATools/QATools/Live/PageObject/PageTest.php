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
		$this->assertCount(1, $page->radioGroup);

		$page->radioGroup->selectOption('1');

		$this->assertTrue($page->radioGroup->isSelected());
	}

}
