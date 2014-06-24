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
use aik099\QATools\PageObject\Element\INodeElementAware;
use aik099\QATools\PageObject\IPageFactory;
use Behat\Mink\Element\NodeElement;
use Mockery as m;
use tests\aik099\QATools\TestCase;

class ElementCollectionTest extends TestCase
{

	/**
	 * @expectedException \aik099\QATools\PageObject\Exception\ElementCollectionException
	 * @expectedExceptionMessage Collection element class is not set
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\ElementCollectionException::TYPE_ELEMENT_CLASS_MISSING
	 */
	public function testCollectionWithoutElementClassError()
	{
		new CollectionWithoutElementClass();
	}

	public function testCollectionWithNonExistingClass()
	{
		$collection = new CollectionWithNonExistingElementClass();
		$this->assertInstanceOf('\\aik099\\QATools\\PageObject\\Element\\AbstractElementCollection', $collection);
	}

	/**
	 * @dataProvider creatingCollectionWithElementMatchByClassDataProvider
	 */
	public function testCreatingCollectionWithElementMatchByClass($element)
	{
		$collection = new CollectionWithExistingElementClass(array($element));
		$this->assertCount(1, $collection);
	}

	public function creatingCollectionWithElementMatchByClassDataProvider()
	{
		return array(
			array(new \stdClass()),
			array(new SubStdClass()),
		);
	}

	/**
	 * @dataProvider creatingCollectionWithElementMatchByInterfaceDataProvider
	 */
	public function testCreatingCollectionWithElementMatchByInterface($element)
	{
		$collection = new CollectionWithExistingElementInterface(array($element));
		$this->assertCount(1, $collection);
	}

	public function creatingCollectionWithElementMatchByInterfaceDataProvider()
	{
		return array(
			array(m::mock('\\tests\\aik099\\QATools\\PageObject\\Element\\ISampleElementInterface')),
			array(m::mock('\\tests\\aik099\\QATools\\PageObject\\Element\\ISubSampleElementInterface')),
		);
	}

	/**
	 * @expectedException \aik099\QATools\PageObject\Exception\ElementCollectionException
	 * @expectedExceptionMessage Collection element must be of "\stdClass" class, but element of "tests\aik099\QATools\PageObject\Element\NonMatchingClass" class given
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\ElementCollectionException::TYPE_ELEMENT_CLASS_MISMATCH
	 * @dataProvider nowLaterDataProvider
	 */
	public function testCreatingCollectionWithNoMatchByElementClass($from_constructor)
	{
		$element = new NonMatchingClass();

		if ( $from_constructor ) {
			new CollectionWithExistingElementClass(array($element));
		}
		else {
			$collection = new CollectionWithExistingElementClass();
			$collection[] = $element;
		}
	}

	/**
	 * @expectedException \aik099\QATools\PageObject\Exception\ElementCollectionException
	 * @expectedExceptionMessage Collection element must be of "\tests\aik099\QATools\PageObject\Element\ISampleElementInterface" class, but element of "tests\aik099\QATools\PageObject\Element\NonMatchingClass" class given
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\ElementCollectionException::TYPE_ELEMENT_CLASS_MISMATCH
	 * @dataProvider nowLaterDataProvider
	 */
	public function testCreatingCollectionWithNoMatchByElementInterface($from_constructor)
	{
		$element = new NonMatchingClass();

		if ( $from_constructor ) {
			new CollectionWithExistingElementInterface(array($element));
		}
		else {
			$collection = new CollectionWithExistingElementInterface();
			$collection[] = $element;
		}
	}

	public function testFromNodeElementsDefaultsToCollectionClass()
	{
		$element = $this->createNodeElement();
		$collection = NodeElementAwareCollection::fromNodeElements(array($element));
		$this->assertCount(1, $collection);
	}

	/**
	 * @expectedException \aik099\QATools\PageObject\Exception\ElementCollectionException
	 * @expectedExceptionMessage Collection element class "\tests\aik099\QATools\PageObject\Element\ISampleElementInterface" must implement INodeElementAware interface
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\ElementCollectionException::TYPE_INCORRECT_ELEMENT_CLASS
	 */
	public function testFromNodeElementElementCantWorkWithInterfaces()
	{
		$element = $this->createNodeElement();
		CollectionWithExistingElementInterface::fromNodeElements(array($element));
	}

	/**
	 * @dataProvider nowLaterDataProvider
	 */
	public function testElementFiltering($from_constructor)
	{
		$element1 = new \stdClass();
		$element2 = new SubStdClass();

		if ( $from_constructor ) {
			$collection = new CollectionWithDirectFilter(array($element1, $element2));
		}
		else {
			$collection = new CollectionWithDirectFilter();
			$collection[] = $element1;
			$collection[] = $element2;
		}

		$this->assertCount(1, $collection);
	}

	public function nowLaterDataProvider()
	{
		return array(
			'from_constructor' => array(true),
			'after_creating' => array(false),
		);
	}

}

class CollectionWithoutElementClass extends AbstractElementCollection
{

}

class CollectionWithNonExistingElementClass extends AbstractElementCollection
{

	public function __construct(array $elements = array())
	{
		$this->elementClass = '\\Some\\NonExisting\\Class';

		parent::__construct($elements);
	}

}

class CollectionWithExistingElementClass extends AbstractElementCollection
{

	public function __construct(array $elements = array())
	{
		$this->elementClass = '\\stdClass';

		parent::__construct($elements);
	}

}

class SubStdClass extends \stdClass
{

}

class CollectionWithExistingElementInterface extends AbstractElementCollection
{

	public function __construct(array $elements = array())
	{
		$this->elementClass = '\\tests\\aik099\\QATools\\PageObject\\Element\\ISampleElementInterface';

		parent::__construct($elements);
	}

}

interface ISampleElementInterface
{

}

interface ISubSampleElementInterface extends ISampleElementInterface
{

}

class NonMatchingClass
{

}

class NodeElementAwareCollection extends AbstractElementCollection
{

	public function __construct(array $elements = array())
	{
		$this->elementClass = '\\tests\\aik099\\QATools\\PageObject\\Element\\NodeElementAwareClass';

		parent::__construct($elements);
	}

}

class NodeElementAwareClass implements INodeElementAware
{

	public static function fromNodeElement(NodeElement $node_element, IPageFactory $page_factory = null)
	{
		return new static();
	}

}

class CollectionWithDirectFilter extends CollectionWithExistingElementClass
{

	protected function acceptElement($element)
	{
		return get_class($element) == ltrim($this->elementClass, '\\');
	}

}
