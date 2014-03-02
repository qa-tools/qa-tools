<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\BEM\PropertyDecorator;


use aik099\QATools\BEM\Annotation\BEMAnnotation;
use Mockery as m;
use tests\aik099\QATools\PageObject\PropertyDecorator\DefaultPropertyDecoratorTest;

class BEMPropertyDecoratorTest extends DefaultPropertyDecoratorTest
{

	/**
	 * Prepares page.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->locatorClass = '\\aik099\\QATools\\BEM\\ElementLocator\\BEMElementLocator';
		$this->decoratorClass = '\\aik099\\QATools\\BEM\\PropertyDecorator\\BEMPropertyDecorator';

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testBlockProxy()
	{
		$element_class = '\\aik099\\QATools\\BEM\\Element\\Block';

		$search_context = m::mock('\\aik099\\QATools\\BEM\\BEMPage');
		$this->locator->shouldReceive('getSearchContext')->andReturn($search_context);

		$node_element = $this->createNodeElement();
		$this->locator->shouldReceive('findAll')->once()->andReturn(array($node_element));

		$annotation = new BEMAnnotation();
		$annotation->block = 'block-name';
		$this->_expectBEMAnnotation($element_class, array($annotation));

		$decorator = m::mock('\\aik099\\QATools\\PageObject\\PropertyDecorator\\IPropertyDecorator');
		$this->pageFactory->shouldReceive('createDecorator')->once()->andReturn($decorator);
		$this->pageFactory->shouldReceive('initElements')->once()->andReturn($this->pageFactory);

		$proxy = $this->decorator->decorate($this->property);
		$this->assertProxy($proxy, '\\aik099\\QATools\\BEM\\Proxy\\BlockProxy', $search_context, $element_class);
		$this->assertNotEmpty($proxy->getNodes());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testElementProxy()
	{
		$element_class = '\\aik099\\QATools\\BEM\\Element\\Element';

		$search_context = m::mock('\\aik099\\QATools\\BEM\\Element\\IBlock');
		$this->locator->shouldReceive('getSearchContext')->andReturn($search_context);

		$node_element = $this->createNodeElement();
		$this->locator->shouldReceive('find')->once()->andReturn($node_element);

		$annotation = new BEMAnnotation();
		$annotation->element = 'element-name';
		$this->_expectBEMAnnotation($element_class, array($annotation));

		$proxy = $this->decorator->decorate($this->property);
		$this->assertProxy($proxy, '\\aik099\\QATools\\BEM\\Proxy\\ElementProxy', $search_context, $element_class);
		$this->assertInstanceOf('\\aik099\\QATools\\PageObject\\Element\\IWebElement', $proxy->getWrappedElement());
	}

	/**
	 * Test description.
	 *
	 * @param string $part_class BEM part class.
	 *
	 * @return void
	 * @dataProvider bemPartDataProvider
	 * @expectedException \aik099\QATools\PageObject\Exception\AnnotationException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\AnnotationException::TYPE_REQUIRED
	 */
	public function testMissingAnnotationError($part_class)
	{
		$this->_expectBEMAnnotation($part_class, array());
		$this->decorator->decorate($this->property);
	}

	/**
	 * Test description.
	 *
	 * @param string $part_class BEM part class.
	 *
	 * @return void
	 * @dataProvider bemPartDataProvider
	 * @expectedException \aik099\QATools\PageObject\Exception\AnnotationException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\AnnotationException::TYPE_INCORRECT_USAGE
	 */
	public function testEmptyAnnotationError($part_class)
	{
		$annotation = new BEMAnnotation();
		$this->_expectBEMAnnotation($part_class, array($annotation));
		$this->decorator->decorate($this->property);
	}

	/**
	 * Test description.
	 *
	 * @param string $part_class BEM part class.
	 *
	 * @return void
	 * @dataProvider bemPartDataProvider
	 * @expectedException \aik099\QATools\PageObject\Exception\AnnotationException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\AnnotationException::TYPE_INCORRECT_USAGE
	 */
	public function testElementWithBlockAnnotationError($part_class)
	{
		$annotation = new BEMAnnotation();
		$annotation->block = 'block-name';
		$annotation->element = 'element-name';
		$this->_expectBEMAnnotation($part_class, array($annotation));
		$this->decorator->decorate($this->property);
	}

	/**
	 * Returns possible BEM parts.
	 *
	 * @return array
	 */
	public function bemPartDataProvider()
	{
		return array(
			array('\\aik099\\QATools\\BEM\\Element\\Block'),
			array('\\aik099\\QATools\\BEM\\Element\\Element'),
		);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\PageObject\Exception\AnnotationException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\AnnotationException::TYPE_INCORRECT_USAGE
	 */
	public function testBlockUsedInWrongContext()
	{
		$search_context = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');
		$this->locator->shouldReceive('getSearchContext')->andReturn($search_context);

		$annotation = new BEMAnnotation();
		$annotation->block = 'block-name';
		$this->_expectBEMAnnotation('\\aik099\\QATools\\BEM\\Element\\Block', array($annotation));

		$this->decorator->decorate($this->property);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\PageObject\Exception\AnnotationException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\AnnotationException::TYPE_INCORRECT_USAGE
	 */
	public function testElementUsedInWrongContext()
	{
		$search_context = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');
		$this->locator->shouldReceive('getSearchContext')->andReturn($search_context);

		$annotation = new BEMAnnotation();
		$annotation->element = 'element-name';
		$this->_expectBEMAnnotation('\\aik099\\QATools\\BEM\\Element\\Element', array($annotation));

		$this->decorator->decorate($this->property);
	}

	/**
	 * Expects, that annotations will be queried from property, which data type is $element_class.
	 *
	 * @param string $element_class Element class.
	 * @param array  $annotations   Annotations.
	 *
	 * @return void
	 */
	private function _expectBEMAnnotation($element_class, array $annotations)
	{
		$this->property->shouldReceive('isSimpleDataType')->andReturn(false);
		$this->property->shouldReceive('getDataType')->andReturn($element_class);

		$this->property
			->shouldReceive('getAnnotationsFromPropertyOrClass')
			->with('@bem')
			->once()
			->andReturn($annotations);
	}

}
