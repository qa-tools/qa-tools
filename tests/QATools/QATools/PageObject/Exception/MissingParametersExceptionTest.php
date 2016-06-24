<?php

namespace tests\QATools\QATools\PageObject\Exception;


use QATools\QATools\PageObject\Exception\MissingParametersException;

class MissingParametersExceptionTest extends \PHPUnit_Framework_TestCase
{

	public function testCanConstruct()
	{
		$parameters = array(
			'some-parameter',
			'another-parameter',
		);

		$exception = new MissingParametersException($parameters);

		$this->assertEquals(
			'No parameters for "some-parameter, another-parameter" masks given.',
			$exception->getMessage()
		);
	}

}
