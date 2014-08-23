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
	protected $pageNamespacePrefixes;

	/**
	 * Creates the DefaultPageLocator.
	 *
	 * @param array $page_namespace_prefixes The page namespace prefixes.
	 */
	public function __construct(array $page_namespace_prefixes)
	{
		$this->pageNamespacePrefixes = (array)$page_namespace_prefixes;
	}

	/**
	 * Returns the fully qualified class name of a page by its name.
	 *
	 * @param string $name The name of the page.
	 *
	 * @return string
	 * @throws PageFactoryException When no class is found.
	 */
	public function getFullyQualifiedClassNameByName($name)
	{
		$class_name = $this->buildClassNameFromName($name);

		if ( $this->isClassExisting($class_name) ) {
			return $class_name;
		}

		foreach ( $this->pageNamespacePrefixes as $prefix ) {
			$fully_qualified_class_name = $prefix . '\\' . $class_name;

			if ( $this->isClassExisting($fully_qualified_class_name) ) {
				return $fully_qualified_class_name;
			}
		}

		throw new PageFactoryException(sprintf('"%s" was not found.'), PageFactoryException::TYPE_PAGE_CLASS_NOT_FOUND);
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

		$class_name = '';

		foreach ( $class_name_parts as $part ) {
			$class_name .= ucfirst($part);
		}

		return $class_name;
	}

	/**
	 * Checks if the given class is existing.
	 *
	 * @param string $fully_qualified_class_name The fully qualified class name to check.
	 *
	 * @return boolean
	 */
	protected function isClassExisting($fully_qualified_class_name)
	{
		return class_exists($fully_qualified_class_name);
	}

}

