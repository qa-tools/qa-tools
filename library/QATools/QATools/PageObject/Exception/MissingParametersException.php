<?php

namespace QATools\QATools\PageObject\Exception;


class MissingParametersException extends \Exception
{

	/**
	 * MissingParametersException constructor.
	 *
	 * @param array $parameters Names of missing parameters.
	 */
	public function __construct(array $parameters)
	{
		$message = sprintf('No parameters for "%s" masks given.', implode(', ', $parameters));
		parent::__construct($message);
	}

}
