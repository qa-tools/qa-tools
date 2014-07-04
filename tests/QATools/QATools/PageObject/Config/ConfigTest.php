<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Config;


use QATools\QATools\PageObject\Config\Config;
use Mockery as m;
use tests\QATools\QATools\TestCase;

class ConfigTest extends TestCase
{

	/**
	 * @dataProvider constructorDataProvider
	 */
	public function testConstructor(array $options, array $expected_values)
	{
		$config = new Config($options);

		foreach ( $expected_values as $expected_name => $expected_value ) {
			$this->assertEquals($expected_value, $config->getOption($expected_name));
		}
	}

	public function constructorDataProvider()
	{
		return array(
			array(
				array(),
				array('base_url' => ''),
			),
			array(
				array('base_url' => 'value'),
				array('base_url' => 'value'),
			),
		);
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\ConfigException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\ConfigException::TYPE_NOT_FOUND
	 */
	public function testConstructorWithFailure()
	{
		new Config(array('non_predefined_key' => 'value'));
	}

	/**
	 * @dataProvider optionDataProvider
	 */
	public function testSetAndGetOption($name, $value)
	{
		$config = new Config();

		$config->setOption($name, $value);

		$this->assertEquals($value, $config->getOption($name));
	}

	public function optionDataProvider()
	{
		return array(
			array('base_url', 'value'),
		);
	}

	/**
	 * @dataProvider getOptionWithFailureDataProvider
	 *
	 * @expectedException \QATools\QATools\PageObject\Exception\ConfigException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\ConfigException::TYPE_NOT_FOUND
	 */
	public function testGetOptionWithFailure(array $options, $name)
	{
		$config = new Config($options);

		$config->getOption($name);
	}

	public function getOptionWithFailureDataProvider()
	{
		return array(
			'non_existing_option' => array(array(), 'name'),
			'option_with_null_value' => array(array('null_value' => null), 'null_value'),
		);
	}

}
