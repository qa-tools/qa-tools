<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\aik099\QATools\HtmlElements\Annotation;


use aik099\QATools\HtmlElements\Annotation\ElementNameAnnotation;

class ElementNameAnnotationTest extends \PHPUnit_Framework_TestCase
{

	public function testInitAnnotation()
	{
		$expected = 'test';

		$annotation = new ElementNameAnnotation();
		$annotation->initAnnotation(array(0 => $expected));
		$this->assertEquals($expected, $annotation->name);
	}

}
