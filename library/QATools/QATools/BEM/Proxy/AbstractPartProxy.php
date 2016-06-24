<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\BEM\Proxy;


use QATools\QATools\BEM\Element\IPart;
use QATools\QATools\BEM\ElementLocator\BEMElementLocator;
use QATools\QATools\PageObject\Proxy\AbstractProxy;
use QATools\QATools\PageObject\IPageFactory;

/**
 * Class for lazy-proxy creation to ensure, that BEM elements are really accessed only at moment, when user needs them.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 *
 * @link http://bit.ly/qa-tools-page-factory-lazy-initialization
 */
abstract class AbstractPartProxy extends AbstractProxy implements IPart
{

	/**
	 * Name.
	 *
	 * @var string
	 */
	private $_name;

	/**
	 * Initializes BEM proxy.
	 *
	 * @param string            $name         Name.
	 * @param BEMElementLocator $locator      Locator.
	 * @param IPageFactory      $page_factory Page factory.
	 */
	public function __construct($name, BEMElementLocator $locator, IPageFactory $page_factory)
	{
		if ( !$this->elementClass ) {
			$this->elementClass = '\\QATools\\QATools\\BEM\\Element\\IPart';
		}

		parent::__construct($locator, $page_factory);

		$this->_name = $name;
	}

	/**
	 * Returns name of the entity.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

}
