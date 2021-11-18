<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Element;


use PHPUnit\Framework\Error\Notice;
use PHPUnit\Framework\Error\Warning;
use QATools\QATools\PageObject\Element\AbstractElementCollection;
use Mockery as m;
use Mockery\MockInterface;
use tests\QATools\QATools\TestCase;

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

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		parent::setUpTest();

		$this->beforeSetUpFinish();

		$this->element = $this->createElement();
	}

	/**
	 * Occurs before "setUpTest" method is finished configuration jobs.
	 *
	 * @return void
	 */
	protected function beforeSetUpFinish()
	{

	}

	public function testArrayAccessInterface()
	{
		$element = $this->createValidElementMock();

		$initial_count = count($this->element);
		$new_count = $initial_count + 1;

		$this->element[] = $element;
		$this->element[$initial_count] = $element;
		$this->assertCount($new_count, $this->element);

		$this->assertSame($element, $this->element[$initial_count]);

		// Alias class not included in forward compatibility class list.
		if ( !\class_exists('PHPUnit\Framework\Error\Notice') ) {
			\class_alias('PHPUnit_Framework_Error_Notice', 'PHPUnit\Framework\Error\Notice');
		}

		if ( !\class_exists('PHPUnit\Framework\Error\Warning') ) {
			\class_alias('PHPUnit_Framework_Error_Warning', 'PHPUnit\Framework\Error\Warning');
		}

		try {
			$this->assertNull($this->element[$new_count]);
		}
		catch ( Notice $e ) {
			// Ignore notice on PHP < 8.0.
		}
		catch ( Warning $e ) {
			// Ignore warning on PHP >= 8.0.
		}

		$this->assertArrayHasKey($initial_count, $this->element);
		unset($this->element[$initial_count]);
		$this->assertArrayNotHasKey($initial_count, $this->element);
	}

	/**
	 * @depends testArrayAccessInterface
	 */
	public function testIteratorInterface()
	{
		$start_index = count($this->element);

		$elements = array(
			$this->createValidElementMock(),
			$this->createValidElementMock(),
			$this->createValidElementMock(),
		);

		foreach ( $elements as $element ) {
			$this->element[] = $element;
		}

		foreach ( $this->element as $index => $element ) {
			if ( $index < $start_index ) {
				continue;
			}

			$this->assertSame($elements[$index - $start_index], $element, 'element at correct index returned');
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
	 * @param boolean $replace_element_class Replace element class.
	 *
	 * @return AbstractElementCollection
	 */
	protected function createElement($replace_element_class = true)
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
