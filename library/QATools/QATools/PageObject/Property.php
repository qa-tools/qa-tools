<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject;


use mindplay\annotations\AnnotationManager;
use mindplay\annotations\IAnnotation;
use mindplay\annotations\standard\VarAnnotation;

/**
 * Represents property, that can be decorated.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class Property extends \ReflectionProperty
{

	/**
	 * Property data type.
	 *
	 * @var string
	 */
	protected $dataType;

	/**
	 * Annotation manager.
	 *
	 * @var AnnotationManager
	 */
	protected $annotationManager;

	/**
	 * Create class instance from reflection property.
	 *
	 * @param \ReflectionProperty $property           Reflection property.
	 * @param AnnotationManager   $annotation_manager Annotation manager.
	 */
	public function __construct(\ReflectionProperty $property, AnnotationManager $annotation_manager)
	{
		parent::__construct($property->class, $property->name);

		$this->annotationManager = $annotation_manager;
	}

	/**
	 * Determines property data type.
	 *
	 * @return string
	 */
	public function getDataType()
	{
		$data_type = $this->getRawDataType();

		if ( !$data_type ) {
			return '';
		}

		return preg_replace('/\[\]$/', '', $data_type);
	}

	/**
	 * Returns the raw data type.
	 *
	 * @return string
	 */
	public function getRawDataType()
	{
		if ( !isset($this->dataType) ) {
			/* @var $annotations VarAnnotation[] */
			$annotations = $this->annotationManager->getPropertyAnnotations($this, null, '@var');

			if ( $annotations && ($annotations[0] instanceof VarAnnotation) ) {
				$this->dataType = $annotations[0]->type;
			}
			else {
				$this->dataType = '';
			}
		}

		return $this->dataType;
	}

	/**
	 * Determines if property data type isn't class or interface.
	 *
	 * @return boolean
	 */
	public function isSimpleDataType()
	{
		$data_types = array(
			'bool', 'int',
			'mixed', 'string', 'boolean', 'integer', 'float', 'double', 'array',
		);

		return in_array(strtolower($this->getDataType()), $data_types);
	}

	/**
	 * Determines if property data type is an array.
	 *
	 * @return boolean
	 */
	public function isDataTypeArray()
	{
		return substr($this->getRawDataType(), -2) == '[]';
	}

	/**
	 * Returns annotation of a property.
	 *
	 * @param string $annotation_class Annotation name.
	 *
	 * @return IAnnotation[]
	 */
	public function getAnnotations($annotation_class)
	{
		return $this->annotationManager->getPropertyAnnotations($this, null, $annotation_class);
	}

	/**
	 * Returns annotations defined at property or in class, set in `@var` annotation of a property.
	 *
	 * @param string $annotation_class Annotation name.
	 *
	 * @return IAnnotation[]
	 */
	public function getAnnotationsFromPropertyOrClass($annotation_class)
	{
		$annotations = $this->getAnnotations($annotation_class);

		if ( !$annotations ) {
			$annotations = $this->annotationManager->getClassAnnotations($this->getDataType(), $annotation_class);
		}

		return $annotations;
	}

	/**
	 * Returns string representation of a property.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->class . '::' . $this->name;
	}

}
