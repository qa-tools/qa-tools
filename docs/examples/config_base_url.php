<?php
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Session;
use QATools\QATools\PageObject\Config\Config;
use QATools\QATools\PageObject\PageFactory;

$session = new Session(new Selenium2Driver());
$config = new Config(array(
    'base_url' => 'http://www.example.com',
));
$page_factory = new PageFactory($session, $config);
