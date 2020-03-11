<?php

namespace tests\QATools\QATools\PageObject\Exception;


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use QATools\QATools\PageObject\Exception\MissingParametersException;

class MissingParametersExceptionTest extends TestCase
{

	use MockeryPHPUnitIntegration;

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
