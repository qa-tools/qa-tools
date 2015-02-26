<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Fixture\Matcher;


use QATools\QATools\PageObject\Matcher\AbstractPageMatcher;
use QATools\QATools\PageObject\Page;

/**
 * Responsible for matching given pages against displayed page.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class AlwaysMatchMatcher extends AbstractPageMatcher
{

	/**
	 * Matches the given page against the open.
	 *
	 * @param Page $page Page to match.
	 *
	 * @return boolean
	 * @throws \QATools\QATools\PageObject\Exception\PageMatcherException When no matches specified.
	 */
	public function matches(Page $page)
	{
		return true;
	}

}
