<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM;


use aik099\QATools\BEM\ElementLocator\BEMElementLocatorFactory;
use aik099\QATools\BEM\ElementLocator\LocatorHelper;
use aik099\QATools\BEM\PropertyDecorator\BEMPropertyDecorator;
use aik099\QATools\PageObject\Config\IConfig;
use aik099\QATools\PageObject\ISearchContext;
use aik099\QATools\PageObject\PageFactory;
use aik099\QATools\PageObject\PropertyDecorator\IPropertyDecorator;
use Behat\Mink\Session;

/**
 * Factory class to make using BEMPage Objects simpler and easier.
 */
class BEMPageFactory extends PageFactory
{

	/**
	 * Locator helper.
	 *
	 * @var LocatorHelper
	 */
	private $_locatorHelper;

	/**
	 * Creates BEMPageFactory instance.
	 *
	 * @param Session       $session        Mink session.
	 * @param IConfig       $config         Page factory configuration.
	 * @param LocatorHelper $locator_helper Locator helper.
	 */
	public function __construct(Session $session, IConfig $config = null, LocatorHelper $locator_helper = null)
	{
		$this->annotationRegistry['bem'] = '\\aik099\\QATools\\BEM\\Annotation\\BEMAnnotation';

		parent::__construct($session, $config);

		$this->_locatorHelper = isset($locator_helper) ? $locator_helper : new LocatorHelper();
	}

	/**
	 * Creates default decorator.
	 *
	 * @param ISearchContext $search_context Search context.
	 *
	 * @return IPropertyDecorator
	 */
	public function createDecorator(ISearchContext $search_context)
	{
		$locator_factory = new BEMElementLocatorFactory($search_context, $this->annotationManager, $this->_locatorHelper);

		return new BEMPropertyDecorator($locator_factory, $this);
	}

}
