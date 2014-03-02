<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\HtmlElements\Element;


use aik099\QATools\HtmlElements\Exception\FormException;
use aik099\QATools\PageObject\Element\WebElement;

/**
 * Form element.
 */
class Form extends HtmlElement
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
			$form_element = $this->typify($this->getWebElement($field_name));

			$this->setValue($form_element, $field_value);
		}

		return $this;
	}

	/**
	 * Finds WebElement by a given field name.
	 *
	 * @param string $field_name Field name to search for.
	 *
	 * @return WebElement
	 * @throws FormException When element for a field name not found.
	 */
	public function getWebElement($field_name)
	{
		$node_element = $this->find('named', array('field', $field_name));

		if ( is_null($node_element) ) {
			throw new FormException(
				sprintf('Form field "%s" not found', $field_name),
				FormException::TYPE_NOT_FOUND
			);
		}

		return WebElement::fromNodeElement($node_element);
	}

	/**
	 * Create TypifiedElement from a given WebElement.
	 *
	 * @param WebElement $web_element Web Element.
	 *
	 * @return TypifiedElement
	 * @throws FormException When unable to create typified element.
	 */
	public function typify(WebElement $web_element)
	{
		$tag_name = $web_element->getTagName();

		if ( $tag_name == 'input' ) {
			$input_type = $web_element->getAttribute('type');

			if ( $input_type == self::CHECKBOX_INPUT ) {
				return new Checkbox($web_element);
			}
			elseif ( $input_type == self::RADIO_INPUT ) {
				return new RadioGroup($web_element);
			}
			elseif ( $input_type == self::FILE_INPUT ) {
				return new FileInput($web_element);
			}
			else {
				/*if ( is_null($input_type) || ($input_type == self::TEXT_INPUT) || ($input_type == self::PASSWORD_INPUT) ) {*/
				return new TextInput($web_element);
			}
		}
		elseif ( $tag_name == 'select' ) {
			return new Select($web_element);
		}
		elseif ( $tag_name == 'textarea' ) {
			return new TextInput($web_element);
		}

		throw new FormException(
			'Unable create typified element for ' . (string)$web_element,
			FormException::TYPE_UNKNOWN_FIELD
		);
	}

	/**
	 * Sets value to the form element.
	 *
	 * @param TypifiedElement $typified_element Element, to set a value for.
	 * @param mixed           $value            Element value to set.
	 *
	 * @return self
	 * @throws FormException When element doesn't support value changing.
	 */
	public function setValue(TypifiedElement $typified_element, $value)
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
