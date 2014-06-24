<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\aik099\QATools\HtmlElementsLive\Element;


use aik099\QATools\HtmlElements\Element\Form;

class FormTest extends TypifiedElementTestCase
{

	protected function setUp()
	{
		parent::setUp();

		$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\Form';
	}

	public function testWaitFor()
	{
		$page = $this->session->getPage();
		$this->assertNull($page->findById('delayed-element'));

		$page->findById('generate-btn')->click();

		/* @var $form Form */
		$form = $this->createElement(array('id' => 'test-form'));
		$delayed_element = $form->waitFor(2000, function (Form $given_form) {
			return $given_form->find('css', '#delayed-element');
		});
		$this->assertNull($delayed_element);

		$delayed_element = $form->waitFor(3000, function (Form $given_form) {
			return $given_form->find('css', '#delayed-element');
		});
		$this->assertInstanceOf('Behat\\Mink\\Element\\NodeElement', $delayed_element);
	}

}
