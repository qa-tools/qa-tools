<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\aik099\QATools\PageObject\Element;


use aik099\QATools\PageObject\Element\AbstractElementCollection;
use Mockery as m;
use Mockery\MockInterface;
use tests\aik099\QATools\TestCase;

abstract class AbstractElementCollectionTestCase extends TestCase
{

	/**
	 * Collection class.
	 *
	 * @var string
	 */
	protected $collectionClass;

	/**
	 * Collection class.
	 *
	 * @var string
	 */
	protected $collectionElementClass = '';

	/**
	 * Collection.
	 *
	 * @var AbstractElementCollection
	 */
	protected $element;

	protected function setUp()
	{
		parent::setUp();

		$this->beforeSetUpFinish();

		$this->element = $this->createElement();
	}

	/**
	 * Occurs before "setUp" method is finished configuration jobs.
	 *
	 * @return void
	 */
	protected function beforeSetUpFinish()
	{

	}

	public function testArrayAccessInterface()
	{
		$element = $this->createValidElementMock();

		$this->assertCount(0, $this->element);
		$this->element[] = $element;
		$this->element[0] = $element;
		$this->assertCount(1, $this->element);

		$this->assertSame($element, $this->element[0]);
		$this->assertNull($this->element[1]);

		$this->assertTrue(isset($this->element[0]));
		unset($this->element[0]);
		$this->assertFalse(isset($this->element[0]));
	}

	/**
	 * @depends testArrayAccessInterface
	 */
	public function testIteratorInterface()
	{
		$elements = array(
			$this->createValidElementMock(),
			$this->createValidElementMock(),
			$this->createValidElementMock(),
		);

		foreach ( $elements as $element ) {
			$this->element[] = $element;
		}

		foreach ( $this->element as $index => $element ) {
			$this->assertSame($elements[$index], $element, 'element at correct index returned');
		}
	}

	/**
	 * Creates valid collection element mock.
	 *
	 * @return MockInterface
	 */
	protected function createValidElementMock()
	{
		return m::mock($this->collectionElementClass);
	}

	/**
	 * Create element.
	 *
	 * @return AbstractElementCollection
	 */
	protected function createElement()
	{
		return new $this->collectionClass();
	}

	/**
	 * Mocks element.
	 *
	 * @param array $methods  Methods to mock.
	 * @param array $elements Elements.
	 *
	 * @return AbstractElementCollection
	 */
	protected function mockCollection(array $methods = array(), array $elements = array())
	{
		if ( $methods ) {
			$method_string = '[' . implode(',', $methods) . ']';

			return m::mock($this->collectionClass . $method_string, array($elements));
		}

		return new $this->collectionClass($elements);
	}

}
