<?php
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Session;
use QATools\QATools\PageObject\Config\Config;
use QATools\QATools\PageObject\Container;
use QATools\QATools\PageObject\PageFactory;

// 1. Obtain/create Mink's session object:
$session = new Session(new Selenium2Driver());

// 2a. Either configure page factory via Config class:
$config = new Config(array(
    'base_url' => 'http://www.example.com',
));
$page_factory = new PageFactory($session, $config);

// 2b. Or configure page factory via dependency injection container:
$container = new Container();
$container['config_options'] = array(
    'base_url' => 'http://www.example.com',
);
$page_factory = new PageFactory($session, $container);
