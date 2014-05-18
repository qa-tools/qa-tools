---
layout:     page
title:      PageObject
categories: [examples]
gh-file:    examples/01-PageObject.md
---

Page with login box and currency selector.

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
$home_page->setUsername('example user');
```

## Page (class: Page)

```php
<?php
use aik099\QATools\PageObject\Page;
use aik099\QATools\PageObject\Element\WebElement;
use aik099\QATools\PageObject\Element\WebElementCollection;

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

	/**
	 *
	 * @var WebElementCollection
	 * @find-by('css' => 'select')
	 */
	protected $selectCollection;

	public function setUsername($username)
	{
		$this->inputByName->setValue($username);
		$this->selectByTagName->setValue('EUR');

		$this->inputByName->setValue('another user');
		
		// Need `getObject` to iterate over current collection in proxy and not a list of collection.
		foreach ( $this->selectCollection->getObject() as $select ) {
			$select->selectOption(/* ... */);
		}
	}
}
```


## LoginSidebox (class: AbstractElementContainer)

```php
<?php
use aik099\QATools\PageObject\Element\AbstractElementContainer;
use aik099\QATools\PageObject\Element\WebElement;

class LoginSidebox extends AbstractElementContainer {

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

## Sidebar (class: AbstractElementContainer)

```php
<?php
use aik099\QATools\PageObject\Element\AbstractElementContainer;

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
