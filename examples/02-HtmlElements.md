---
layout:     page
title:      HtmlElements
categories: [examples]
gh-file:    examples/02-HtmlElements.md
---

On a page there is a sidebar on the left. Inside a sidebar there are multiple sideboxes, and one of them is LoginBox. LoginBox consists of 2 inputs and submit button.

![Example Page](/examples/example_page.png)

```html
<html>
	<head></head>
	<body>
		<div class="header">
			Language:
			<select name="language">
				<option value="1">English</option>
				<option value="2">Russian</option>
			</select>
			Currency:
			<select name="curr_iso">
				<option value="USD">USD</option>
				<option value="EUR">EUR</option>
			</select>
		</div>
		<div id="block-sidebar">
			<div id="login-sidebox">
				<div class="field-error"></div>
				E-mail or Username: <input type="text" name="u.login-sidebox[-2][UserLogin]" value=""/><br/>
				Password: <input type="password" name="u.login-sidebox[-2][UserPassword]" value=""/><br/>
				<input type="submit" name="events[u.login-sidebox][OnLogin]" value="Login"/>
			</div>
		</div>
	</body>
</html>
```

## Usage

```php
<?php
$session = new \Behat\Mink\Session(new \Behat\Mink\Driver\Selenium2Driver());

$home_page = new HomePage($session);
$home_page->open();
$home_page->examplePageMethod();
```

## Page (class: TypifiedPage)

```php
<?php
use aik099\QATools\HtmlElements\TypifiedPage;
use aik099\QATools\PageObject\Element\WebElement;
use aik099\QATools\HtmlElements\Element\Select;
use aik099\QATools\HtmlElements\Element\Button;
use aik099\QATools\HtmlElements\Element\RadioGroup;

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
	 *
	 * @var RadioGroup
	 * @find-by('css' => 'input[name="radio-name"][type="radio"]')
	 * @element-name('Custom Element Name')
	 */
	protected $radioGroup;

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

		foreach ( $this->radioGroup as $radioButton ) {
			if ( $radioButton->isSelected() ) {
				echo 'yes';
			}
		}

		$this->radioGroup->selectButtonByValue(4);

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

## LoginSidebox (class: AbstractElementContainer)

```php
<?php
use aik099\QATools\HtmlElements\Element\AbstractElementContainer;
use aik099\QATools\HtmlElements\Element\TextInput;
use aik099\QATools\HtmlElements\Element\Button;
use aik099\QATools\HtmlElements\Element\TextBlock;

/**
 * @element-name('default element name')
 */
class LoginSidebox extends AbstractElementContainer {

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

## Sidebar (class: AbstractElementContainer)

```php
<?php
use aik099\QATools\HtmlElements\Element\AbstractElementContainer;

/**
 * @find-by('id' => 'block-sidebar')
 */
class Sidebar extends AbstractElementContainer {

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
