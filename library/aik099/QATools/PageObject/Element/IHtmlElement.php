<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\Element;


use aik099\QATools\PageObject\ISearchContext;

/**
 * Interface to allow HtmlElement class detection in proxies.
 */
interface IHtmlElement extends IContainerAware, ISearchContext
{

}
