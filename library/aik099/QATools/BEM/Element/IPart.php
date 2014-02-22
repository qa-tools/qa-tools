<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM\Element;


use aik099\QATools\HtmlElements\Element\INamed;
use aik099\QATools\PageObject\Element\IContainerAware;

/**
 * Interface, that every BEM block/element must implement.
 */
interface IPart extends INamed, IContainerAware
{

}
