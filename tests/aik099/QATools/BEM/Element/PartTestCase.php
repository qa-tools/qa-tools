<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\BEM\Element;


use aik099\QATools\BEM\Element\AbstractPart;
use Mockery as m;
use tests\aik099\QATools\TestCase;

abstract class PartTestCase extends TestCase
{

	/**
	 * AbstractPart class.
	 *
	 * @var string
	 */
	protected $partClass = '';

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetContainer()
	{
		$container = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');

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
