<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\Live;


use QATools\QATools\PageObject\Config\Config;
use QATools\QATools\PageObject\Container;
use QATools\QATools\PageObject\Page;
use QATools\QATools\PageObject\PageFactory;

abstract class AbstractLivePageTestCase extends AbstractLiveTestCase
{

	public function testOpeningPageUsingConfigInjection()
	{
		$config = new Config(array(
			'base_url' => $_SERVER['WEB_FIXTURE_URL'],
		));

		$page_factory = new $this->pageFactoryClass($this->session, $config);

		$page = $this->createRelativePage($page_factory);
		$page->open();

		$this->assertContains('direct_open=1', $this->session->getCurrentUrl(), 'Correct URL is opened');
	}

	public function testOpeningPageUsingContainerInjection()
	{
		$config = new Config(array(
			'base_url' => $_SERVER['WEB_FIXTURE_URL'],
		));

		$container = new Container();
		$container['config'] = $config;

		$page_factory = new $this->pageFactoryClass($this->session, $container);

		$page = $this->createRelativePage($page_factory);
		$page->open();

		$this->assertContains('direct_open=1', $this->session->getCurrentUrl(), 'Correct URL is opened');
	}

	/**
	 * Creates page with relative url.
	 *
	 * @param PageFactory $page_factory Page factory.
	 *
	 * @return Page
	 */
	abstract protected function createRelativePage(PageFactory $page_factory);

}
