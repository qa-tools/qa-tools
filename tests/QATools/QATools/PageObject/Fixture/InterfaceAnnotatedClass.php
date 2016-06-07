<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Fixture;


class InterfaceAnnotatedClass
{

	/**
	 * Testing property using an Interface as type.
	 *
	 * @var IExampleAnnotation
	 */
	protected $property;

}

interface IExampleAnnotation
{

}
