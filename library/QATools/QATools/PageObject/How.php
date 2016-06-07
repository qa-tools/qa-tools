<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject;


/**
 * Ways of finding element on a page in Selenium style.
 */
final class How
{

	const ID = 'id';

	const NAME = 'name';

	const ID_OR_NAME = 'idOrName';

	const CSS = 'css';

	const CLASS_NAME = 'className';

	const LINK_TEXT = 'linkText';

	const PARTIAL_LINK_TEXT = 'partialLinkText';

	const TAG_NAME = 'tagName';

	const XPATH = 'xpath';

	const LABEL = 'label';

}
