---
layout: page
title:  BEM
categories:  [examples]
---

On a page there is a sidebar on the left. Inside a sidebar there are multiple sideboxes, and one of them is LoginBox. LoginBox consists of 2 inputs and submit button.

![Example Page](/examples/example_page.png)

```html
<html>
	<head></head>
	<body>
		<div class="b-header">
			Language:
			<select name="b-header__language">
				<option value="1">English</option>
				<option value="2">Russian</option>
			</select>
			Currency:
			<select name="b-header__curr-iso">
				<option value="USD">USD</option>
				<option value="EUR">EUR</option>
			</select>
		</div>
		<div class="b-sidebar">
			<div class="b-login_location_sidebar">
				<div class="b-login__error-msg"></div>
				E-mail or Username: <input type="text" class="b-login__input-username" value=""/><br/>
				Password: <input type="password" class="b-login__input-password_color_red" value=""/><br/>
				<input type="submit" class="b-login__btn-login" value="Login"/>
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

## Page (class: BEMPage)

```php
<?php
use aik099\QATools\BEM\BEMPage;
use aik099\QATools\BEM\Element\Element;

/**
 * @page-url('index')
 */
class HomePage extends BEMPage {

	/**
	 * Login Sidebox
	 *
	 * @var LoginSidebox
	 * @bem('block' => 'b-login', 'modifier' => array('location' => 'sidebar'))
	 */
	protected $loginSidebox;

	/**
	 * Name of the test
	 *
	 * @var string
	 */
	protected $myTest = '';

	public function examplePageMethod()
	{
		// html block login
		$error_message = $this->loginSidebox->login('user-b', 'password-b')->getLoginErrorMessage();
	}
}
```

## LoginSidebox (class: Block)

```php
<?php
use aik099\QATools\BEM\Element\Block;
use aik099\QATools\BEM\Element\Element;

/**
 * @bem('block' => 'b-login')
 */
class LoginSidebox extends Block {

	/**
	 * @var Element
	 * @bem('element' => 'input-username')
	 */
	protected $username;

	/**
	 * @var Element
	 * @bem('element' => 'input-password', 'modifier' => array('color' => 'red'))
	 */
	protected $password;

	/**
	 * @var Element
	 * @bem('element' => 'btn-login')
	 */
	protected $loginButton;

	/**
	 * @var Element
	 * @bem('element' => 'error-msg')
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
