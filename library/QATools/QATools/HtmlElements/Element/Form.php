<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\HtmlElements\Element;


use QATools\QATools\HtmlElements\Exception\FormException;
use QATools\QATools\PageObject\Element\WebElement;
use Behat\Mink\Element\NodeElement;

/**
 * Form element.
 */
class Form extends AbstractElementContainer
{

	const TEXT_INPUT = 'text';

	const PASSWORD_INPUT = 'password';

	const CHECKBOX_INPUT = 'checkbox';

	const RADIO_INPUT = 'radio';

	const FILE_INPUT = 'file';

	/**
	 * Fills the form with given data.
	 *
	 * @param array $form_data Associative array with keys matching field names.
	 *
	 * @return self
	 */
	public function fill(array $form_data)
	{
		foreach ( $form_data as $field_name => $field_value ) {
			$form_element = $this->typify($this->getNodeElements($field_name));

			$this->setValue($form_element, $field_value);
		}

		return $this;
	}

	/**
	 * Finds NodeElements by a given field name.
	 *
	 * @param string $field_name Field name to search for.
	 *
	 * @return NodeElement[]
	 * @throws FormException When element for a field name not found.
	 */
	public function getNodeElements($field_name)
	{
		$node_elements = $this->findAll(
			'named',
			array('field', $this->getXpathEscaper()->escapeLiteral($field_name))
		);

		if ( empty($node_elements) ) {
			throw new FormException(
				sprintf('Form field "%s" not found', $field_name),
				FormException::TYPE_NOT_FOUND
			);
		}

		return $node_elements;
	}

	/**
	 * Create AbstractTypifiedElement from a given NodeElements.
	 *
	 * @param array|NodeElement[] $node_elements Node Elements.
	 *
	 * @return ITypifiedElement
	 * @throws FormException When unable to create typified element.
	 */
	public function typify(array $node_elements)
	{
		$node_element = $node_elements[0];
		$tag_name = $node_element->getTagName();

		if ( $tag_name == 'input' ) {
			$input_type = $node_element->getAttribute('type');

			if ( $input_type == self::CHECKBOX_INPUT ) {
				return Checkbox::fromNodeElement($node_element, $this->getPageFactory());
			}
			elseif ( $input_type == self::RADIO_INPUT ) {
				return RadioGroup::fromNodeElements($node_elements, null, $this->getPageFactory());
			}
			elseif ( $input_type == self::FILE_INPUT ) {
				return FileInput::fromNodeElement($node_element, $this->getPageFactory());
			}
			else {
				/*if ( $input_type === null
					|| ($input_type == self::TEXT_INPUT)
					|| ($input_type == self::PASSWORD_INPUT)
				) {*/
				return TextInput::fromNodeElement($node_element, $this->getPageFactory());
			}
		}
		elseif ( $tag_name == 'select' ) {
			return Select::fromNodeElement($node_element, $this->getPageFactory());
		}
		elseif ( $tag_name == 'textarea' ) {
			return TextInput::fromNodeElement($node_element, $this->getPageFactory());
		}

		$web_element = WebElement::fromNodeElement($node_element, $this->getPageFactory());
		throw new FormException(
			'Unable create typified element for ' . (string)$web_element,
			FormException::TYPE_UNKNOWN_FIELD
		);
	}

	/**
	 * Sets value to the form element.
	 *
	 * @param ITypifiedElement $typified_element Element, to set a value for.
	 * @param mixed            $value            Element value to set.
	 *
	 * @return self
	 * @throws FormException When element doesn't support value changing.
	 */
	public function setValue(ITypifiedElement $typified_element, $value)
	{
		if ( $typified_element instanceof ISimpleSetter ) {
			$typified_element->setValue($value);

			return $this;
		}

		throw new FormException(
			'Element ' . (string)$typified_element . ' doesn\'t support value changing',
			FormException::TYPE_READONLY_FIELD
		);
	}

	/**
	 * Submits a form.
	 *
	 * @return self
	 */
	public function submit()
	{
		$this->getWrappedElement()->submit();

		return $this;
	}

}
