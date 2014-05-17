<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM\Proxy;


use aik099\QATools\BEM\Element\IPart;
use aik099\QATools\BEM\ElementLocator\BEMElementLocator;
use aik099\QATools\PageObject\Proxy\AbstractProxy;
use aik099\QATools\PageObject\IPageFactory;

/**
 * Class for lazy-proxy creation to ensure, that BEM elements are really accessed only at moment, when user needs them.
 *
 * @method \Mockery\Expectation shouldReceive
 *
 * @link http://bit.ly/14TbcR9
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
	public function __construct($name, BEMElementLocator $locator, IPageFactory $page_factory = null)
	{
		if ( !$this->elementClass ) {
			$this->elementClass = '\\aik099\\QATools\\BEM\\Element\\IPart';
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
