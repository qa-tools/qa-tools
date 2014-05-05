<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject;


/**
 * Ways of finding element on a page in Selenium style.
 */
final class How
{

	const CLASS_NAME = 'className';

	const CSS = 'css';

	const ID = 'id';

	/*const ID_OR_NAME = '?';*/

	const LINK_TEXT = 'linkText';

	const NAME = 'name';

	const PARTIAL_LINK_TEXT = 'partialLinkText';

	const TAG_NAME = 'tagName';

	const XPATH = 'xpath';

}
