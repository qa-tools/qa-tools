<?php
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Session;
use QATools\QATools\PageObject\Container;
use QATools\QATools\PageObject\PageFactory;

// 1. Obtain/create Mink's session object:
$session = new Session(new Selenium2Driver());

// 2. Create dependency injection container:
$container = new Container();

// 3. Specify configuration options:
$container['config_options'] = array(
    'base_url' => 'http://www.example.com',
);

// 4. Create page factory.
$page_factory = new PageFactory($session, $container);
