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


/**
 * Represents text input control (such as &lt;input type="text"/&gt; or &lt;textarea/&gt;).
 */
class TextInput extends AbstractFormElement implements ISimpleSetter
{

	/**
	 * List of acceptance criteria.
	 *
	 * @var array
	 */
	protected $acceptanceCriteria = array(
		array('tag' => 'input|textarea'),
	);

	/**
	 * Clears all the text entered into this text input.
	 *
	 * @return self
	 */
	public function clear()
	{
		$this->getWrappedElement()->setValue('');

		return $this;
	}

	/**
	 * Prints specified char sequence into this text input.
	 *
	 * @param mixed $keys Text to print.
	 *
	 * @return self
	 */
	public function sendKeys($keys)
	{
		$this->getWrappedElement()->setValue($keys);

		return $this;
	}

	/**
	 * Retrieves the text entered into this text input.
	 *
	 * @return string Text entered into the text input.
	 */
	public function getText()
	{
		return $this->getWrappedElement()->getValue();
	}

	/**
	 * Sets value to the element.
	 *
	 * @param mixed $value New value.
	 *
	 * @return self
	 */
	public function setValue($value)
	{
		return $this->sendKeys((string)$value);
	}

}
