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


use QATools\QATools\PageObject\Element\WebElement;
use QATools\QATools\PageObject\Page;
use QATools\QATools\PageObject\PageFactory;
use tests\QATools\QATools\Live\AbstractLivePageTestCase;
use tests\QATools\QATools\Live\PageObject\Pages\RelativePage;
use tests\QATools\QATools\Live\PageObject\Pages\WebElementPage;

class PageTest extends AbstractLivePageTestCase
{

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		$this->pageFactoryClass = '\\QATools\\QATools\\PageObject\\PageFactory';
		parent::setUpTest();
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

		$this->assertTextInputs($page->textInputs);
	}

	public function testTextInputsLocatedByMultipleFindByAnnotations()
	{
		/** @var WebElementPage $page */
		$page = new WebElementPage($this->pageFactory);

		$this->assertTextInputs($page->textInputsMultipleFindBy);
	}

	public function testContainerPropertyProxied()
	{
		/** @var WebElementPage $page */
		$page = new WebElementPage($this->pageFactory);

		$page->inputContainer->textInput->setValue('new text');

		$this->assertEquals('new text', $page->inputContainer->textInput->getValue());
	}

	/**
	 * Asserts count and values of text inputs.
	 *
	 * @param WebElement[] $text_inputs Given text inputs.
	 */
	protected function assertTextInputs($text_inputs)
	{
		$this->assertCount(3, $text_inputs);

		$text_inputs->setValue('text');

		$this->assertEquals('text', $text_inputs[0]->getValue());
		$this->assertNotEquals('text', $text_inputs[1]->getValue());
		$this->assertNotEquals('text', $text_inputs[2]->getValue());

		$count = 0;

		foreach ( $text_inputs as $index => $input ) {
			$count++;
			$input->setValue('text' . $index);
		}

		$this->assertEquals(3, $count);

		$this->assertEquals('text0', $text_inputs[0]->getValue());
		$this->assertEquals('text1', $text_inputs[1]->getValue());
		$this->assertEquals('text2', $text_inputs[2]->getValue());
	}

	public function testPageUrlMatchAnchor()
	{
		/** @var WebElementPage $page */
		$page = new WebElementPage($this->pageFactory);

		$this->assertFalse($page->opened());

		$page->linkAnchorTest->click();

		$this->assertTrue($page->opened());
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
