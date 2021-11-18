<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\BEM\PropertyDecorator;


use QATools\QATools\BEM\Annotation\BEMAnnotation;
use Mockery as m;
use tests\QATools\QATools\PageObject\PropertyDecorator\DefaultPropertyDecoratorTest;

class BEMPropertyDecoratorTest extends DefaultPropertyDecoratorTest
{

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		$this->locatorClass = '\\QATools\\QATools\\BEM\\ElementLocator\\BEMElementLocator';
		$this->decoratorClass = '\\QATools\\QATools\\BEM\\PropertyDecorator\\BEMPropertyDecorator';

		parent::setUpTest();
	}

	public function testBlockProxy()
	{
		$element_class = '\\QATools\\QATools\\BEM\\Element\\Block';

		$search_context = m::mock('\\QATools\\QATools\\BEM\\BEMPage');
		$this->locator->shouldReceive('getSearchContext')->andReturn($search_context);

		$node_element = $this->createNodeElement();
		$this->locator->shouldReceive('findAll')->once()->andReturn(array($node_element));

		$annotation = new BEMAnnotation();
		$annotation->block = 'block-name';
		$this->_expectBEMAnnotation($element_class, array($annotation));

		$decorator = m::mock('\\QATools\\QATools\\PageObject\\PropertyDecorator\\IPropertyDecorator');
		$this->pageFactory->shouldReceive('createDecorator')->once()->andReturn($decorator);
		$this->pageFactory->shouldReceive('initElements')->once()->andReturn($this->pageFactory);

		$proxy = $this->decorator->decorate($this->property);
		$this->assertProxy($proxy, '\\QATools\\QATools\\BEM\\Proxy\\BlockProxy', $element_class);
		$this->assertNotEmpty($proxy->getNodes());
	}

	public function testElementProxy()
	{
		$element_class = '\\QATools\\QATools\\BEM\\Element\\Element';

		$search_context = m::mock('\\QATools\\QATools\\BEM\\Element\\IBlock');
		$this->locator->shouldReceive('getSearchContext')->andReturn($search_context);

		$node_elements = array($this->createNodeElement());
		$this->locator->shouldReceive('findAll')->once()->andReturn($node_elements);

		$annotation = new BEMAnnotation();
		$annotation->element = 'element-name';
		$this->_expectBEMAnnotation($element_class, array($annotation));

		$proxy = $this->decorator->decorate($this->property);
		$this->assertProxy($proxy, '\\QATools\\QATools\\BEM\\Proxy\\ElementProxy', $element_class);
		$this->assertInstanceOf('\\QATools\\QATools\\PageObject\\Element\\IWebElement', $proxy->getWrappedElement());
	}

	/**
	 * @dataProvider bemPartDataProvider
	 */
	public function testMissingAnnotationError($part_class)
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\AnnotationException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\AnnotationException::TYPE_REQUIRED);
		$this->expectExceptionMessage('BEM block/element must be specified as annotation');

		$this->_expectBEMAnnotation($part_class, array());
		$this->decorator->decorate($this->property);
	}

	/**
	 * @dataProvider bemPartDataProvider
	 */
	public function testEmptyAnnotationError($part_class)
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\AnnotationException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\AnnotationException::TYPE_INCORRECT_USAGE);
		$this->expectExceptionMessage("Either 'block' or 'element' key with non-empty value must be specified in the annotation");

		$annotation = new BEMAnnotation();
		$this->_expectBEMAnnotation($part_class, array($annotation));
		$this->decorator->decorate($this->property);
	}

	/**
	 * @dataProvider bemPartDataProvider
	 */
	public function testElementWithBlockAnnotationError($part_class)
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\AnnotationException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\AnnotationException::TYPE_INCORRECT_USAGE);
		$this->expectExceptionMessage("Either 'block' or 'element' key with non-empty value must be specified in the annotation");

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
			array('\\QATools\\QATools\\BEM\\Element\\Block'),
			array('\\QATools\\QATools\\BEM\\Element\\Element'),
		);
	}

	public function testBlockUsedInWrongContext()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\AnnotationException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\AnnotationException::TYPE_INCORRECT_USAGE);
		$this->expectExceptionMessage('BEM block can only be used in BEMPage sub-class property');

		$search_context = m::mock('\\QATools\\QATools\\PageObject\\ISearchContext');
		$this->locator->shouldReceive('getSearchContext')->andReturn($search_context);

		$annotation = new BEMAnnotation();
		$annotation->block = 'block-name';
		$this->_expectBEMAnnotation('\\QATools\\QATools\\BEM\\Element\\Block', array($annotation));

		$this->decorator->decorate($this->property);
	}

	public function testElementUsedInWrongContext()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\AnnotationException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\AnnotationException::TYPE_INCORRECT_USAGE);
		$this->expectExceptionMessage('BEM element can only be used in Block sub-class (or any class, implementing IBlock interface) property');

		$search_context = m::mock('\\QATools\\QATools\\PageObject\\ISearchContext');
		$this->locator->shouldReceive('getSearchContext')->andReturn($search_context);

		$annotation = new BEMAnnotation();
		$annotation->element = 'element-name';
		$this->_expectBEMAnnotation('\\QATools\\QATools\\BEM\\Element\\Element', array($annotation));

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
