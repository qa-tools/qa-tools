<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\BEM\ElementLocator;


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use QATools\QATools\BEM\ElementLocator\BEMElementLocatorFactory;
use Mockery as m;
use QATools\QATools\PageObject\SeleniumSelector;

class BEMElementLocatorFactoryTest extends TestCase
{

	use MockeryPHPUnitIntegration;

	const PROPERTY_CLASS = '\\QATools\\QATools\\PageObject\\Property';

	/**
	 * Locator class.
	 *
	 * @var string
	 */
	protected $locatorClass = '\\QATools\\QATools\\BEM\\ElementLocator\\BEMElementLocator';

	public function testCreateLocator()
	{
		$search_context = m::mock('\\QATools\\QATools\\PageObject\\ISearchContext');
		$selenium_selector = m::mock(SeleniumSelector::class);
		$locator_helper = m::mock('\\QATools\\QATools\\BEM\\ElementLocator\\LocatorHelper');
		$factory = new BEMElementLocatorFactory($search_context, $selenium_selector, $locator_helper);

		$property = m::mock(self::PROPERTY_CLASS);
		$locator = $factory->createLocator($property);

		$this->assertInstanceOf($this->locatorClass, $locator);
	}

}
