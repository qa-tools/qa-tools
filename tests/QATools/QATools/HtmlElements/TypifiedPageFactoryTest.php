<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\HtmlElements;


use tests\QATools\QATools\PageObject\PageFactoryTest;
use Mockery as m;

class TypifiedPageFactoryTest extends PageFactoryTest
{

	/**
	 * Some property for the test.
	 *
	 * @var string
	 */
	protected $typifiedProperty;

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		$this->factoryClass = '\\QATools\\QATools\\HtmlElements\\TypifiedPageFactory';
		$this->pageClass = '\\tests\\QATools\\QATools\\HtmlElements\\Fixture\\Page\\TypifiedPageChild';
		$this->decoratorClass = '\\QATools\\QATools\\HtmlElements\\PropertyDecorator\\TypifiedPropertyDecorator';

		parent::setUpTest();
	}

}
