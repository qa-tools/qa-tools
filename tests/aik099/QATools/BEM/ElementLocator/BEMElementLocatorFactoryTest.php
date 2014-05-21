<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\BEM\ElementLocator;


use aik099\QATools\BEM\ElementLocator\BEMElementLocatorFactory;
use Mockery as m;

class BEMElementLocatorFactoryTest extends \PHPUnit_Framework_TestCase
{

	const PROPERTY_CLASS = '\\aik099\\QATools\\PageObject\\Property';

	/**
	 * Locator class.
	 *
	 * @var string
	 */
	protected $locatorClass = '\\aik099\\QATools\\BEM\\ElementLocator\\BEMElementLocator';

	public function testCreateLocator()
	{
		$annotation_manager = m::mock('\\mindplay\\annotations\\AnnotationManager');
		$search_context = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');
		$locator_helper = m::mock('\\aik099\\QATools\\BEM\\ElementLocator\\LocatorHelper');
		$factory = new BEMElementLocatorFactory($search_context, $annotation_manager, $locator_helper);

		$property = m::mock(self::PROPERTY_CLASS);
		$locator = $factory->createLocator($property);

		$this->assertInstanceOf($this->locatorClass, $locator);
	}

}
