<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\HtmlElements;


use tests\aik099\QATools\PageObject\PageFactoryTest;
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
	 * Prepares test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->factoryClass = '\\aik099\\QATools\\HtmlElements\\TypifiedPageFactory';
		$this->pageClass = '\\tests\\aik099\\QATools\\HtmlElements\\Fixture\\Page\\TypifiedPageChild';
		$this->decoratorClass = '\\aik099\\QATools\\HtmlElements\\PropertyDecorator\\TypifiedPropertyDecorator';

		parent::setUp();
	}

}
