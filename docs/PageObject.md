# Overview
Page with login box and currency selector.

![Example Page](example_page.png)

## Usage
```php
$session = new \Behat\Mink\Session(new \Behat\Mink\Driver\Selenium2Driver());

$home_page = new HomePage($session);
$home_page->open();
$home_page->setUsername('example user');
```

## Page (class: Page)
```php
use aik099\QATools\PageObject\Page;
use aik099\QATools\PageObject\Elements\WebElement;

/**
 * @page-url('index')
 */
class HomePage extends Page {

	/**
	 *
	 * @var WebElement
	 * @find-by('name' => 'u.login-sidebox[-2][UserLogin]')
	 */
	protected $inputByName;

	/**
	 *
	 * @var WebElement
	 * @find-by('css' => 'select[name="curr_iso"]')
	 */
	protected $selectByTagName;

	public function setUsername($username)
	{
		$this->inputByName->setValue($username);
		$this->selectByTagName->setValue('EUR');

		$this->inputByName->setValue('another user');
	}
}
```


## LoginSidebox (class: HtmlElement)
```php
use aik099\QATools\PageObject\Elements\HtmlElement;
use aik099\QATools\PageObject\Elements\WebElement;

class LoginSidebox extends HtmlElement {

	/**
	 * @var WebElement
	 * @find-by('name' => 'u.login-sidebox[-2][UserLogin]')
	 */
	protected $username;

	/**
	 * @var WebElement
	 * @find-by('name' => 'u.login-sidebox[-2][UserPassword]')
	 */
	protected $password;

	/**
	 * @var WebElement
	 * @find-by('name' => 'events[u.login-sidebox][OnLogin]')
	 */
	protected $loginButton;

	/**
	 * @var WebElement
	 * @find-by('css' => '.field-error')
	 */
	protected $loginErrorMessage;

	/**
	 * Tries to login a user
	 *
	 * @param string $username
	 * @param string $password
	 * @return LoginSidebox
	 */
	public function login($username, $password)
	{
		$this->username->setValue($username);
		$this->password->setValue($password);
		$this->loginButton->click();

		return $this;
	}

	/**
	 * Returns error message after login
	 *
	 * @return string
	 */
	public function getLoginErrorMessage()
	{
		return $this->loginErrorMessage->getText();
	}
}
```

## Sidebar (class: HtmlElement)
```php
use aik099\QATools\PageObject\Elements\HtmlElement;

/**
 * @find-by('id' => 'block-sidebar')
 */
class Sidebar extends HtmlElement {

	/**
	 * Login Sidebox
	 *
	 * @var LoginSidebox
	 * @find-by('id' => 'login-sidebox')
	 */
	protected $loginSidebox;

	/**
	 * Tries to login a user
	 *
	 * @param string $username
	 * @param string $password
	 * @return string
	 */
	public function login($username, $password)
	{
		return $this->loginSidebox->login($username, $password)->getLoginErrorMessage();
	}
}
```