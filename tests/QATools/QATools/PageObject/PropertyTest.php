<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject;


use mindplay\annotations\standard\VarAnnotation;
use Mockery as m;
use QATools\QATools\PageObject\Property;

class PropertyTest extends \PHPUnit_Framework_TestCase
{

	const ANNOTATION_MANAGER_CLASS = '\\mindplay\\annotations\\AnnotationManager';

	/**
	 * Property class.
	 *
	 * @var string
	 */
	protected $propertyClass = '\\QATools\\QATools\\PageObject\\Property';

	/**
	 * Property.
	 *
	 * @var Property
	 */
	protected $property;

	/**
	 * Annotation manager.
	 *
	 * @var \Mockery\MockInterface
	 */
	protected $annotationManager;

	protected function setUp()
	{
		parent::setUp();

		$this->annotationManager = m::mock(self::ANNOTATION_MANAGER_CLASS);

		$property = new \ReflectionProperty($this, 'propertyClass');
		$this->property = new $this->propertyClass($property, $this->annotationManager);
	}

	public function testConstructor()
	{
		$reflection_property = new \ReflectionProperty($this, 'propertyClass');
		$property = new $this->propertyClass($reflection_property, $this->annotationManager);

		$this->assertEquals(get_class($this), $property->class);
		$this->assertEquals('propertyClass', $property->name);
	}

	/**
	 * @dataProvider getDataTypeDataProvider
	 */
	public function testGetDataType($data_type, $result)
	{
		$this->expectVarAnnotation($data_type);

		$this->assertSame($result, $this->property->getDataType());
	}

	/**
	 * Expects `@var` annotation.
	 *
	 * @param string|null $data_type Class name.
	 *
	 * @return void
	 */
	protected function expectVarAnnotation($data_type = null)
	{
		$annotations = array();

		if ( isset($data_type) ) {
			$annotation = new VarAnnotation();
			$annotation->type = $data_type;

			$annotations[] = $annotation;
		}

		$this->annotationManager->shouldReceive('getPropertyAnnotations')
			->with($this->property, null, '@var')
			->andReturn($annotations);
	}

	public function getDataTypeDataProvider()
	{
		return array(
			array('SampleClass', 'SampleClass'),
			array('SampleClass[]', 'SampleClass'),
			array(null, ''),
		);
	}

	/**
	 * @dataProvider getRawDataTypeDataProvider
	 */
	public function testGetRawDataType($data_type, $result)
	{
		$this->expectVarAnnotation($data_type);

		$this->assertSame($result, $this->property->getRawDataType());
	}

	public function getRawDataTypeDataProvider()
	{
		return array(
			array('SampleClass', 'SampleClass'),
			array('SampleClass[]', 'SampleClass[]'),
			array(null, ''),
		);
	}

	/**
	 * @dataProvider dataTypeProvider
	 */
	public function testIsSimpleDataType($data_type, $is_simple)
	{
		$reflection_property = new \ReflectionProperty($this, 'propertyClass');

		/* @var $property Property */
		$property = m::mock(
			$this->propertyClass . '[getDataType]',
			array($reflection_property, $this->annotationManager)
		);
		$property->shouldReceive('getDataType')->once()->andReturn($data_type);

		$this->assertSame($is_simple, $property->isSimpleDataType());
	}

	public function dataTypeProvider()
	{
		return array(
			array('bool', true),
			array('int', true),
			array('mixed', true),
			array('string', true),
			array('boolean', true),
			array('integer', true),
			array('float', true),
			array('double', true),
			array('array', true),
			array($this->propertyClass, false),
		);
	}

	/**
	 * @dataProvider isDataTypeArrayProvider
	 */
	public function testIsDataTypeArray($data_type, $is_array)
	{
		$this->expectVarAnnotation($data_type);

		$this->assertSame($is_array, $this->property->isDataTypeArray());
	}

	public function isDataTypeArrayProvider()
	{
		return array(
			array('SampleClass[]', true),
			array('SampleClass', false),
			array(null, false),
		);
	}

	/**
	 * @dataProvider isDataTypeCollectionProvider
	 */
	public function testIsDataTypeCollection($data_type, $is_collection)
	{
		$this->expectVarAnnotation($data_type);

		$this->assertSame($is_collection, $this->property->isDataTypeCollection());
	}

	public function isDataTypeCollectionProvider()
	{
		return array(
			array('stdClass', false),
			array('tests\\QATools\\QATools\\PageObject\\Fixture\\Element\\WebElementCollectionChild', true),
		);
	}

	public function testGetAnnotations()
	{
		$expected = 'OK';
		$this->annotationManager
			->shouldReceive('getPropertyAnnotations')
			->with($this->property, null, 'A')
			->once()
			->andReturn($expected);

		$this->assertEquals($expected, $this->property->getAnnotations('A'));
	}

	public function testGetAnnotationsFromPropertyOrClass()
	{
		$expected = 'OK';
		$this->annotationManager
			->shouldReceive('getPropertyAnnotations')
			->with($this->property, null, 'A')
			->once()
			->andReturn($expected);

		$this->assertEquals($expected, $this->property->getAnnotationsFromPropertyOrClass('A'));
	}

	public function testGetAnnotationsFromPropertyOrClassFallback()
	{
		$var_annotation = new VarAnnotation();
		$var_annotation->type = 'DT';

		$expected = 'OK';
		$this->annotationManager
			->shouldReceive('getPropertyAnnotations')
			->with($this->property, null, '@var')
			->once()
			->andReturn(
				array($var_annotation)
			);
		$this->annotationManager
			->shouldReceive('getPropertyAnnotations')
			->with($this->property, null, 'A')
			->once()
			->andReturn(array());
		$this->annotationManager
			->shouldReceive('getClassAnnotations')
			->with('DT', 'A')
			->once()
			->andReturn('OK');

		$this->assertEquals($expected, $this->property->getAnnotationsFromPropertyOrClass('A'));
	}

	public function testToString()
	{
		$this->assertEquals(get_class($this) . '::propertyClass', (string)$this->property);
	}

}
