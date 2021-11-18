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
use QATools\QATools\PageObject\Exception\ConfigException;
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
				array(
					'base_url' => '',
					'page_namespace_prefix' => array('\\'),
					'page_url_matchers' => array(
						'\\QATools\\QATools\\PageObject\\PageUrlMatcher\\ExactPageUrlMatcher',
						'\\QATools\\QATools\\PageObject\\PageUrlMatcher\\RegexpPageUrlMatcher',
						'\\QATools\\QATools\\PageObject\\PageUrlMatcher\\ComponentPageUrlMatcher',
					),
				),
			),
			array(
				array(
					'base_url' => 'override',
					'page_namespace_prefix' => array('\\CustomNS'),
					'page_url_matchers' => array(
						'CustomPageUrlMatcher',
					),
				),
				array(
					'base_url' => 'override',
					'page_namespace_prefix' => array('\\CustomNS'),
					'page_url_matchers' => array(
						'CustomPageUrlMatcher',
					),
				),
			),
		);
	}

	public function testConstructorWithFailure()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\ConfigException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\ConfigException::TYPE_NOT_FOUND);
		$this->expectExceptionMessage('Option "non_predefined_key" doesn\'t exist in configuration');

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
			array('base_url', 'override1'),
			array('page_namespace_prefix', 'override2'),
			array('page_url_matchers', 'override3'),
		);
	}

	/**
	 * @dataProvider getOptionWithFailureDataProvider
	 */
	public function testGetOptionWithFailure(array $options, $name)
	{
		$this->expectException('QATools\\QATools\\PageObject\\Exception\\ConfigException');
		$this->expectExceptionMessage('Option "' . $name . '" doesn\'t exist in configuration');
		$this->expectExceptionCode(ConfigException::TYPE_NOT_FOUND);

		$config = new Config($options);
		$config->getOption($name);
	}

	/**
	 * @dataProvider getOptionWithFailureDataProvider
	 */
	public function testSetOptionWithFailure(array $options, $name)
	{
		$this->expectException('QATools\\QATools\\PageObject\\Exception\\ConfigException');
		$this->expectExceptionMessage('Option "' . $name . '" doesn\'t exist in configuration');
		$this->expectExceptionCode(ConfigException::TYPE_NOT_FOUND);

		$config = new Config($options);
		$config->setOption($name, 'x');
	}

	public function getOptionWithFailureDataProvider()
	{
		return array(
			'non_existing_option' => array(array(), 'name'),
			'option_with_null_value' => array(array('null_value' => null), 'null_value'),
		);
	}

}
