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


use QATools\QATools\BEM\Element\Block;
use QATools\QATools\BEM\ElementLocator\BEMElementLocator;
use Behat\Mink\Element\NodeElement;
use Mockery as m;
use Mockery\MockInterface;

class BlockTest extends PartTestCase
{

	/**
	 * Locator, used for searching.
	 *
	 * @var array
	 */
	private $_locator = array('className' => 'block-name__element-name');

	/**
	 * Block elements.
	 *
	 * @var NodeElement[]|MockInterface[]
	 */
	private $_nodes;

	/**
	 * Locator helper.
	 *
	 * @var BEMElementLocator|MockInterface
	 */
	private $_elementLocator;

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		parent::setUpTest();

		$this->partClass = '\\tests\\QATools\\QATools\\BEM\\Fixture\\Element\\BlockChild';

		$this->pageFactory->shouldReceive('initElements')->once()->andReturn($this->pageFactory);

		$decorator = m::mock('\\QATools\\QATools\\PageObject\\PropertyDecorator\\IPropertyDecorator');
		$this->pageFactory->shouldReceive('createDecorator')->once()->andReturn($decorator);

		$this->_nodes = array(
			$this->createNodeElement('top-xpath-1'),
			$this->createNodeElement('top-xpath-2'),
		);

		$this->_elementLocator = m::mock('\\QATools\\QATools\\BEM\\ElementLocator\\BEMElementLocator');
	}

	public function testConstructor()
	{
		$block = $this->createPart();
		$this->assertSame('block-name', $block->getName());
		$this->assertSame($this->_nodes, $block->getNodes());
	}

	/**
	 * @dataProvider modificatorDataProvider
	 */
	public function testGetElement($modificator_name, $modificator_value)
	{
		$this->_prepareSearchFixture();

		$this->_elementLocator
			->shouldReceive('getElementLocator')
			->with('element-name', 'block-name', $modificator_name, $modificator_value)
			->once()
			->andReturn($this->_locator);

		$block = $this->createPart();
		$node = $block->getElement('element-name', $modificator_name, $modificator_value);

		$this->assertEquals('sub-xpath-1', $node->getXpath());
	}

	/**
	 * @dataProvider modificatorDataProvider
	 */
	public function testGetElementEmpty($modificator_name, $modificator_value)
	{
		$this->_prepareSearchFixture(true);

		$this->_elementLocator
			->shouldReceive('getElementLocator')
			->with('element-name', 'block-name', $modificator_name, $modificator_value)
			->once()
			->andReturn($this->_locator);

		$block = $this->createPart();
		$node = $block->getElement('element-name', $modificator_name, $modificator_value);

		$this->assertNull($node);
	}

	/**
	 * @dataProvider modificatorDataProvider
	 */
	public function testGetElements($modificator_name, $modificator_value)
	{
		$this->_prepareSearchFixture();

		$this->_elementLocator
			->shouldReceive('getElementLocator')
			->with('element-name', 'block-name', $modificator_name, $modificator_value)
			->once()
			->andReturn($this->_locator);

		$block = $this->createPart();
		$nodes = $block->getElements('element-name', $modificator_name, $modificator_value);

		$this->assertCount(2, $nodes);
		$this->assertEquals('sub-xpath-1', $nodes[0]->getXpath());
		$this->assertEquals('sub-xpath-2', $nodes[1]->getXpath());
	}

	/**
	 * @dataProvider modificatorDataProvider
	 */
	public function testGetElementsEmpty($modificator_name, $modificator_value)
	{
		$this->_prepareSearchFixture(true);

		$this->_elementLocator
			->shouldReceive('getElementLocator')
			->with('element-name', 'block-name', $modificator_name, $modificator_value)
			->once()
			->andReturn($this->_locator);

		$block = $this->createPart();
		$nodes = $block->getElements('element-name', $modificator_name, $modificator_value);

		$this->assertCount(0, $nodes);
	}

	public function modificatorDataProvider()
	{
		return array(
			array(null, null),
			array('modificator-name', 'modificator-value'),
		);
	}

	public function testFind()
	{
		$this->_prepareSearchFixture();

		$block = $this->createPart();
		$node = $block->find('se', $this->_locator);

		$this->assertEquals('sub-xpath-1', $node->getXpath());
	}

	public function testFindEmpty()
	{
		$this->_prepareSearchFixture(true);

		$block = $this->createPart();
		$node = $block->find('se', $this->_locator);

		$this->assertNull($node);
	}

	public function testFindAll()
	{
		$this->_prepareSearchFixture();

		$block = $this->createPart();
		$nodes = $block->findAll('se', $this->_locator);

		$this->assertCount(2, $nodes);
		$this->assertEquals('sub-xpath-1', $nodes[0]->getXpath());
		$this->assertEquals('sub-xpath-2', $nodes[1]->getXpath());
	}

	public function testFindAllEmpty()
	{
		$this->_prepareSearchFixture(true);

		$block = $this->createPart();
		$nodes = $block->findAll('se', $this->_locator);

		$this->assertCount(0, $nodes);
	}

	/**
	 * Prepares search fixture.
	 *
	 * @param boolean $empty_result Should result be empty.
	 *
	 * @return void
	 */
	private function _prepareSearchFixture($empty_result = false)
	{
		$locator = array('className' => 'block-name__element-name');

		$result1 = $result2 = array();

		if ( !$empty_result ) {
			$result1[] = $this->createNodeElement('sub-xpath-1');
			$result2[] = $this->createNodeElement('sub-xpath-2');
		}

		$this->selectorsHandler
			->shouldReceive('selectorToXpath')
			->with('se', $locator)
			->andReturn('BEM_XPATH');

		$this->driver
			->shouldReceive('find')
			->with($this->_nodes[0]->getXpath() . '/BEM_XPATH')
			->andReturn($result1);

		$this->driver
			->shouldReceive('find')
			->with($this->_nodes[1]->getXpath() . '/BEM_XPATH')
			->andReturn($result2);
	}

	/**
	 * Create part.
	 *
	 * @return Block
	 */
	protected function createPart()
	{
		return new $this->partClass('block-name', $this->_nodes, $this->pageFactory, $this->_elementLocator);
	}

}
