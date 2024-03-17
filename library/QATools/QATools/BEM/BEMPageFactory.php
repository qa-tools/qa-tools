<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\BEM;


use QATools\QATools\BEM\ElementLocator\BEMElementLocatorFactory;
use QATools\QATools\BEM\ElementLocator\LocatorHelper;
use QATools\QATools\BEM\PropertyDecorator\BEMPropertyDecorator;
use QATools\QATools\PageObject\Config\Config;
use QATools\QATools\PageObject\Config\IConfig;
use QATools\QATools\PageObject\Container;
use QATools\QATools\PageObject\ISearchContext;
use QATools\QATools\PageObject\PageFactory;
use QATools\QATools\PageObject\PropertyDecorator\IPropertyDecorator;
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
	 * @param Session               $session             Mink session.
	 * @param Container|Config|null $container_or_config Dependency injection container_or_config or Config.
	 * @param LocatorHelper         $locator_helper      Locator helper.
	 */
	public function __construct(Session $session, $container_or_config = null, LocatorHelper $locator_helper = null)
	{
		$this->annotationRegistry['bem'] = '\\QATools\\QATools\\BEM\\Annotation\\BEMAnnotation';

		parent::__construct($session, $container_or_config);

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
		$locator_factory = new BEMElementLocatorFactory($search_context, $this->_locatorHelper);

		return new BEMPropertyDecorator($locator_factory, $this);
	}

}
