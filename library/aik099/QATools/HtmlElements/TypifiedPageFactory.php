<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\HtmlElements;


use Behat\Mink\Session;
use mindplay\annotations\AnnotationManager;
use aik099\QATools\HtmlElements\PropertyDecorator\TypifiedPropertyDecorator;
use aik099\QATools\PageObject\ElementLocator\DefaultElementLocatorFactory;
use aik099\QATools\PageObject\ISearchContext;
use aik099\QATools\PageObject\PageFactory;
use aik099\QATools\PageObject\PropertyDecorator\IPropertyDecorator;

/**
 * Factory class to make using Page Objects simpler and easier.
 */
class TypifiedPageFactory extends PageFactory
{

	/**
	 * Creates TypifiedPageFactory instance.
	 *
	 * @param Session                $session            Mink session.
	 * @param AnnotationManager|null $annotation_manager Annotation manager.
	 */
	public function __construct(Session $session, AnnotationManager $annotation_manager = null)
	{
		$this->annotationRegistry['element-name'] = '\\aik099\\QATools\\HtmlElements\\Annotation\\ElementNameAnnotation';

		parent::__construct($session, $annotation_manager);
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
