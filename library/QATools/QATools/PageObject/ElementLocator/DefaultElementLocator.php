<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\ElementLocator;


use Behat\Mink\Element\NodeElement;
use QATools\QATools\PageObject\Annotation\FindByAnnotation;
use QATools\QATools\PageObject\Exception\AnnotationException;
use QATools\QATools\PageObject\Exception\ElementException;
use QATools\QATools\PageObject\ISearchContext;
use QATools\QATools\PageObject\Property;
use QATools\QATools\PageObject\SeleniumSelector;

/**
 * Class, that locates WebElements.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class DefaultElementLocator implements IElementLocator
{

	/**
	 * Search context.
	 *
	 * @var ISearchContext
	 */
	protected $searchContext;

	/**
	 * Property.
	 *
	 * @var Property
	 */
	protected $property;

	/**
	 * Selenium selector.
	 *
	 * @var SeleniumSelector
	 */
	protected $seleniumSelector;

	/**
	 * Creates a new element locator.
	 *
	 * @param Property         $property          Property.
	 * @param ISearchContext   $search_context    The context to use when finding the element.
	 * @param SeleniumSelector $selenium_selector Selenium selector.
	 */
	public function __construct(
		Property $property,
		ISearchContext $search_context,
		SeleniumSelector $selenium_selector
	) {
		$this->property = $property;
		$this->searchContext = $search_context;
		$this->seleniumSelector = $selenium_selector;
	}

	/**
	 * Returns search context in use.
	 *
	 * @return ISearchContext
	 */
	public function getSearchContext()
	{
		return $this->searchContext;
	}

	/**
	 * Find the element.
	 *
	 * @return NodeElement|null
	 */
	public function find()
	{
		$items = $this->findAll();

		return count($items) ? current($items) : null;
	}

	/**
	 * Find the element list.
	 *
	 * @return NodeElement[]
	 * @throws ElementException In case no array/collection given and multiple elements found.
	 */
	public function findAll()
	{
		$elements = array();

		foreach ( $this->getSelectors() as $selector ) {
			$how = key($selector);
			$using = $selector[$how];
			$xpath = $this->seleniumSelector->translateToXPath($how, $using);

			$elements = array_merge($elements, $this->searchContext->findAll('xpath', $xpath));
		}

		$element_count = count($elements);

		if ( $element_count > 1 && !$this->property->isDataTypeArray() && !$this->property->isDataTypeCollection() ) {
			throw new ElementException(
				sprintf(
					'The "%s" used on "%s" property expects finding 1 element, but %s elements were found.',
					$this->property->getRawDataType(),
					$this->property,
					$element_count
				),
				ElementException::TYPE_MULTIPLE_ELEMENTS_FOUND
			);
		}

		return $elements;
	}

	/**
	 * Returns final selectors to be used for element locating.
	 *
	 * @return array
	 */
	protected function getSelectors()
	{
		/* @var $annotations FindByAnnotation[] */
		$annotations = $this->property->getAnnotationsFromPropertyOrClass('@find-by');

		$this->assertAnnotationClass($annotations);

		$selectors = array();

		foreach ( $annotations as $annotation ) {
			$selectors[] = $annotation->getSelector();
		}

		return $selectors;
	}

	/**
	 * Asserts that required annotations are present.
	 *
	 * @param array $annotations Annotations to test.
	 *
	 * @return void
	 *
	 * @throws AnnotationException Thrown if none or wrong annotations given.
	 */
	protected function assertAnnotationClass(array $annotations)
	{
		if ( !$annotations ) {
			$parameters = array((string)$this->property, $this->property->getDataType());
			$message = '@find-by must be specified in the property "%s" DocBlock or in class "%s" DocBlock';
			throw new AnnotationException(vsprintf($message, $parameters), AnnotationException::TYPE_REQUIRED);
		}

		foreach ( $annotations as $annotation ) {
			if ( !($annotation instanceof FindByAnnotation) ) {
				$parameters = array((string)$this->property, $this->property->getDataType());
				$message = '@find-by must be specified in the property "%s" DocBlock or in class "%s" DocBlock';
				throw new AnnotationException(vsprintf($message, $parameters), AnnotationException::TYPE_REQUIRED);
			}
		}
	}

	/**
	 * Returns string representation of a locator.
	 *
	 * @return string
	 */
	public function __toString()
	{
		$exported_selectors = array();

		$selectors = $this->getSelectors();

		foreach ( $selectors as $selector ) {
			$exported_selectors[] = $selector;
		}

		return var_export($exported_selectors, true);
	}

}
