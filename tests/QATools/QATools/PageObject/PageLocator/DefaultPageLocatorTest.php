<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\PageLocator;


use Mockery as m;
use QATools\QATools\PageObject\PageLocator\DefaultPageLocator;

class DefaultPageLocatorTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Locator class.
	 *
	 * @var string
	 */
	protected $locatorClass = '\\QATools\\QATools\\PageObject\\PageLocator\\DefaultPageLocator';

	/**
	 * @dataProvider invalidPageClassNamesDataProvider
	 *
	 * @expectedException \QATools\QATools\PageObject\Exception\PageFactoryException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\PageFactoryException::TYPE_PAGE_CLASS_NOT_FOUND
	 */
	public function testResolvePageFailure($class_name)
	{
		/** @var DefaultPageLocator $locator */
		$locator = new $this->locatorClass(array());

		$locator->resolvePage($class_name);
	}

	public function invalidPageClassNamesDataProvider()
	{
		return array(
			array(''),
			array('SomeNonExistingClassName'),
			array('Example\\SomeNonExistingClassName'),
		);
	}

	/**
	 * @dataProvider resolvePageExistingClassGivenDataProvider
	 */
	public function testResolvePageExistingClassGiven($class_name, $expected_resolved_page_class)
	{
		/** @var DefaultPageLocator $locator */
		$locator = new $this->locatorClass(array());

		m::mock($expected_resolved_page_class);

		$this->assertEquals($expected_resolved_page_class, $locator->resolvePage($class_name));
	}

	public function resolvePageExistingClassGivenDataProvider()
	{
		return array(
			array('ResolvePageMockedExistingClass', 'ResolvePageMockedExistingClass'),
			array('resolve page mocked existing class', 'ResolvePageMockedExistingClass'),
			array('Example\\ResolvePageMockedExistingClass', 'Example\\ResolvePageMockedExistingClass'),
		);
	}

	/**
	 * @dataProvider resolvePageUsingPrefixDataProvider
	 */
	public function testResolvePageUsingPrefix($class_name, $prefixes, $expected_resolved_page_class)
	{
		/** @var DefaultPageLocator $locator */
		$locator = new $this->locatorClass($prefixes);

		m::mock($expected_resolved_page_class);

		$this->assertEquals($expected_resolved_page_class, $locator->resolvePage($class_name));
	}

	public function resolvePageUsingPrefixDataProvider()
	{
		return array(
			array(
				'ResolvePageMockedPrefixedClass',
				array(),
				'ResolvePageMockedPrefixedClass',
			),
			array(
				'ResolvePageMockedPrefixedClassInNamespace',
				array('Example'),
				'Example\\ResolvePageMockedPrefixedClassInNamespace',
			),
			array(
				'resolve page mocked prefixed class',
				array(''),
				'ResolvePageMockedPrefixedClass',
			),
			array(
				'resolve page mocked prefixed class in namespace',
				array('Example'),
				'Example\\ResolvePageMockedPrefixedClassInNamespace',
			),
		);
	}

}
