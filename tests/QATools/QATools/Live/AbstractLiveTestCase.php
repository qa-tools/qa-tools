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


use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use QATools\QATools\PageObject\IPageFactory;

abstract class AbstractLiveTestCase extends \PHPUnit_Framework_TestCase
{

	/**
	 * Session manager.
	 *
	 * @var Mink
	 */
	protected static $mink;

	/**
	 * Session.
	 *
	 * @var Session
	 */
	protected $session;

	/**
	 * Page factory.
	 *
	 * @var IPageFactory
	 */
	protected $pageFactory;

	/**
	 * Page factory class.
	 *
	 * @var string
	 */
	protected $pageFactoryClass;

	/**
	 * Creates one session per test case.
	 *
	 * @return void
	 */
	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();

		if ( !is_object(self::$mink) ) {
			self::$mink = new Mink();

			$server_url = 'http://' . $_SERVER['WEB_FIXTURE_HOST'] . ':' . $_SERVER['WEB_FIXTURE_PORT'] . '/wd/hub';
			$driver = new Selenium2Driver($_SERVER['WEB_FIXTURE_BROWSER'], null, $server_url);

			self::$mink->registerSession('phpunit', new Session($driver));
			self::$mink->setDefaultSessionName('phpunit');
		}
	}

	/**
	 * Prepare session and page factory for the tests.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->session = self::$mink->getSession();
		$this->pageFactory = $this->createFactory();
	}

	/**
	 * Creates factory.
	 *
	 * @return IPageFactory
	 */
	protected function createFactory()
	{
		$this->startSession();

		return new $this->pageFactoryClass($this->session);
	}

	/**
	 * Create session.
	 *
	 * @return void
	 */
	protected function startSession()
	{
		if ( !$this->session->isStarted() ) {
			$this->session->start();
		}

		$this->session->visit($_SERVER['WEB_FIXTURE_URL'] . '/tests/QATools/QATools/Live/PageObject/index.html');
	}

	/**
	 * Checks, that content is present on a page.
	 *
	 * @param string $content Content to find.
	 *
	 * @return void
	 */
	protected function assertPageContains($content)
	{
		$page_content = $this->session->getPage()->getContent();

		$this->assertTrue(strpos($page_content, $content) !== false, 'Page contains "' . $content . '" content');
	}

	/**
	 * Stops all sessions, that might have started.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
		parent::tearDown();

		if ( $this->session !== null ) {
			$this->session->reset();
		}
	}

	/**
	 * Stops all sessions, that might have started.
	 *
	 * @return void
	 */
	public static function tearDownAfterClass()
	{
		parent::tearDownAfterClass();

		self::$mink->stopSessions();
	}

}
