# Overview
On a page there is a sidebar on the left. Inside a sidebar there are multiple sideboxes, and one of them is LoginBox. LoginBox consists of 2 inputs and submit button.

![Example Page](example_page.png)

## Usage
```php
$session = new \Behat\Mink\Session(new \Behat\Mink\Driver\Selenium2Driver());

$home_page = new HomePage($session);
$home_page->open();
$home_page->examplePageMethod();
```

## Page (class: TypifiedPage)
```php
use aik099\QATools\HtmlElements\TypifiedPage;
use aik099\QATools\PageObject\Elements\WebElement;
use aik099\QATools\HtmlElements\Elements\Select;
use aik099\QATools\HtmlElements\Elements\Button;

/**
 * @page-url('index')
 */
class HomePage extends TypifiedPage {

	/**
	 *
	 * @var WebElement
	 * @find-by('name' => 'u.login-sidebox[-2][UserLogin]')
	 */
	protected $usernameInput;

	/**
	 *
	 * @var WebElement
	 * @find-by('css' => 'select[name="curr_iso"]')
	 */
	protected $currencyDropdown;

	/**
	 *
	 * @var Select
	 * @find-by('css' => 'select[name="language"]')
	 * @element-name('Custom Element Name')
	 */
	protected $languageDropdown;

	/**
	 * Login Button
	 *
	 * @var Button
	 * @find-by('name' => 'events[u.login-sidebox][OnLogin]')
	 */
	protected $loginButton;

	/**
	 * Login Sidebox
	 *
	 * @var LoginSidebox
	 * @find-by('id' => 'login-sidebox')
	 */
	protected $loginSidebox;

	/**
	 * Sidebar
	 *
	 * @var Sidebar
	 */
	protected $sidebar;

	/**
	 * Name of the test
	 *
	 * @var string
	 */
	protected $myTest = '';

	public function examplePageMethod()
	{
//		$this->currencyDropdown->setValue('EUR');
		$this->languageDropdown->selectByVisibleText('Russian');

		// inline login
		$this->usernameInput->setValue('user-a');
		$this->loginButton->click();

		// html block login
		$error_message = $this->loginSidebox->login('user-b', 'password-b')->getLoginErrorMessage();

		// nested html block login
		$error_message = $this->sidebar->login('user-b', 'password-b');

		// auto-generated name
		echo $this->sidebar->getName();

		// override name
		echo $this->loginSidebox->getName();
	}
}
```

## LoginSidebox (class: HtmlElement)
```php
use aik099\QATools\HtmlElements\Elements\HtmlElement;
use aik099\QATools\HtmlElements\Elements\TextInput;
use aik099\QATools\HtmlElements\Elements\Button;
use aik099\QATools\HtmlElements\Elements\TextBlock;

/**
 * @element-name('default element name')
 */
class LoginSidebox extends HtmlElement {

	/**
	 * @var TextInput
	 * @find-by('name' => 'u.login-sidebox[-2][UserLogin]')
	 */
	protected $username;

	/**
	 * @var TextInput
	 * @find-by('name' => 'u.login-sidebox[-2][UserPassword]')
	 */
	protected $password;

	/**
	 * @var Button
	 * @find-by('name' => 'events[u.login-sidebox][OnLogin]')
	 */
	protected $loginButton;

	/**
	 * @var TextBlock
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
		$this->username->sendKeys($username);
		$this->password->sendKeys($password);
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
use aik099\QATools\HtmlElements\Elements\HtmlElement;

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