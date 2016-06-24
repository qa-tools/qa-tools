<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\HtmlElements;


use QATools\QATools\HtmlElements\PropertyDecorator\TypifiedPropertyDecorator;
use QATools\QATools\PageObject\Config\IConfig;
use QATools\QATools\PageObject\Container;
use QATools\QATools\PageObject\ElementLocator\DefaultElementLocatorFactory;
use QATools\QATools\PageObject\ISearchContext;
use QATools\QATools\PageObject\PageFactory;
use QATools\QATools\PageObject\PropertyDecorator\IPropertyDecorator;
use Behat\Mink\Session;

/**
 * Factory class to make using Page Objects simpler and easier.
 */
class TypifiedPageFactory extends PageFactory
{

	/**
	 * Creates TypifiedPageFactory instance.
	 *
	 * @param Session        $session   Mink session.
	 * @param Container|null $container Dependency injection container.
	 */
	public function __construct(Session $session, Container $container = null)
	{
		$this->annotationRegistry['element-name'] = '\\QATools\\QATools\\HtmlElements\\Annotation\\ElementNameAnnotation';

		parent::__construct($session, $container);
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
		$locator_factory = new DefaultElementLocatorFactory($search_context, $this->annotationManager);

		return new TypifiedPropertyDecorator($locator_factory, $this);
	}

}
