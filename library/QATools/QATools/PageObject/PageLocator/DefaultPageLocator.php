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
	protected $pageNamespacePrefixes = array();

	/**
	 * Creates the DefaultPageLocator.
	 *
	 * @param array $page_namespace_prefixes The page namespace prefixes.
	 */
	public function __construct(array $page_namespace_prefixes)
	{
		foreach ( $page_namespace_prefixes as $namespace ) {
			$this->pageNamespacePrefixes[] = $this->normalize($namespace);
		}
	}

	/**
	 * Returns the fully qualified class name of a page by its name.
	 *
	 * @param string $name The name of the page.
	 *
	 * @return string
	 * @throws PageFactoryException When page class is not found.
	 */
	public function resolvePage($name)
	{
		$possible_pages = $this->buildPossiblePages($name);
		$page_class = $this->findExistingPage($possible_pages);

		if ( $page_class !== false ) {
			return $page_class;
		}

		throw new PageFactoryException(
			sprintf('"%s" was not found.', $name),
			PageFactoryException::TYPE_PAGE_CLASS_NOT_FOUND
		);
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
		$class_name = $this->buildClassNameFromName($name);
		$possible_classes = (array)$class_name;

		foreach ( $this->pageNamespacePrefixes as $prefix ) {
			$possible_classes[] = $prefix . $class_name;
		}

		return $possible_classes;
	}

	/**
	 * Returns first existing class passed in array.
	 *
	 * @param array $possible_pages Possible page classes.
	 *
	 * @return string|bool
	 */
	protected function findExistingPage(array $possible_pages)
	{
		foreach ( $possible_pages as $page_class ) {
			if ( class_exists($page_class) ) {
				return $page_class;
			}
		}

		return false;
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
	 * Normalizes passed namespace.
	 *
	 * @param string $namespace Namespace.
	 *
	 * @return string
	 */
	protected function normalize($namespace)
	{
		$normalized_namespace = trim($namespace, '\\') . '\\';

		if ( strpos($normalized_namespace, '\\') !== 0 ) {
			$normalized_namespace = '\\' . $normalized_namespace;
		}

		return $normalized_namespace;
	}

}

