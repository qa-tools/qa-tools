<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Annotation;


use QATools\QATools\PageObject\Annotation\TimeoutAnnotation;

class TimeoutAnnotationTest extends \PHPUnit_Framework_TestCase
{

	public function testInitAnnotation()
	{
		$expected = 'test';

		$annotation = new TimeoutAnnotation();
		$annotation->initAnnotation(array(0 => $expected));
		$this->assertEquals($expected, $annotation->duration);
	}

}
