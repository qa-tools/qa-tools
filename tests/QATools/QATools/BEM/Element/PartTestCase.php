<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\BEM\Element;


use QATools\QATools\BEM\Element\AbstractPart;
use Mockery as m;
use tests\QATools\QATools\TestCase;

abstract class PartTestCase extends TestCase
{

	/**
	 * AbstractPart class.
	 *
	 * @var string
	 */
	protected $partClass = '';

	public function testSetContainer()
	{
		$container = m::mock('\\QATools\\QATools\\PageObject\\ISearchContext');

		$part = $this->createPart();

		$this->assertSame($part, $part->setContainer($container));
		$this->assertSame($container, $part->getContainer());
	}

	/**
	 * Creates part to be tested.
	 *
	 * @return AbstractPart
	 */
	abstract protected function createPart();

}
