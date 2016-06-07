<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\PageLocator;


use QATools\QATools\PageObject\Exception\PageFactoryException;

/**
 * Class to return fully qualified class names by its name.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class DefaultPageLocator implements IPageLocator
{

	/**
	 * Prefix for page classes.
	 *
	 * @var array
	 */
	protected $namespacePrefixes = array();

	/**
	 * Creates the DefaultPageLocator.
	 *
	 * @param array $namespace_prefixes The page namespace prefixes.
	 *
	 * @throws PageFactoryException When no prefixes are given.
	 */
	public function __construct(array $namespace_prefixes)
	{
		if ( empty($namespace_prefixes) ) {
			throw new PageFactoryException(
				'No namespace prefixes passed',
				PageFactoryException::TYPE_PAGE_MISSING_PREFIXES
			);
		}

		foreach ( $namespace_prefixes as $prefix ) {
			$this->namespacePrefixes[] = $this->normalize($prefix);
		}
	}

	/**
	 * Normalizes passed namespace.
	 *
	 * @param string $namespace Namespace.
	 *
	 * @return string
	 */
	protected function normalize($namespace)
	{
		$normalized_namespace = rtrim($namespace, '\\') . '\\';

		if ( strpos($normalized_namespace, '\\') !== 0 ) {
			$normalized_namespace = '\\' . $normalized_namespace;
		}

		return $normalized_namespace;
	}

	/**
	 * Returns the fully qualified class name of a page by its name.
	 *
	 * @param string $name The name of the page.
	 *
	 * @return string
	 * @throws PageFactoryException When no name is given.
	 */
	public function resolvePage($name)
	{
		if ( empty($name) ) {
			throw new PageFactoryException('No page name given', PageFactoryException::TYPE_PAGE_NAME_MISSING);
		}

		$possible_pages = $this->buildPossiblePages($name);

		return $this->getExistingPageClass($possible_pages);
	}

	/**
	 * Builds all possible page classes from passed name and current prefixes.
	 *
	 * @param string $name Page name.
	 *
	 * @return array
	 */
	protected function buildPossiblePages($name)
	{
		$possible_classes = array();
		$class_name = $this->buildClassNameFromName($name);

		foreach ( $this->namespacePrefixes as $prefix ) {
			$possible_classes[] = $prefix . $class_name;
		}

		return $possible_classes;
	}

	/**
	 * Builds the class name from a given name by uppercasing the first letter of each word and removing the spaces.
	 *
	 * @param string $name The class name.
	 *
	 * @return string
	 */
	protected function buildClassNameFromName($name)
	{
		$class_name_parts = explode(' ', $name);

		return count($class_name_parts) == 1 ? $name : implode('', array_map('ucfirst', $class_name_parts));
	}

	/**
	 * Returns first existing class passed in array.
	 *
	 * @param array $possible_pages Possible page classes.
	 *
	 * @return string
	 * @throws PageFactoryException When page class is not found.
	 */
	protected function getExistingPageClass(array $possible_pages)
	{
		foreach ( $possible_pages as $page_class ) {
			if ( class_exists($page_class) ) {
				return $page_class;
			}
		}

		$message = sprintf(
			'None of the possible classes were found: %s',
			implode($possible_pages, ', ')
		);

		throw new PageFactoryException($message, PageFactoryException::TYPE_PAGE_CLASS_NOT_FOUND);
	}

}

