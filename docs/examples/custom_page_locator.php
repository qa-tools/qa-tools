<?php
use QATools\QATools\PageObject\Exception\PageFactoryException;
use QATools\QATools\PageObject\PageLocator\IPageLocator;

class MappingPageLocator implements IPageLocator
{
    protected $pages = array(
        'Login Page' => '\\shop\\pages\\LoginPage',
        'Registration Page' => '\\shop\\pages\\LoginPage',
        'Landing Page' => '\\shop\\pages\\LoginPage',
        'Account Overview Page' => '\\shop\\account\\pages\\AccountPage',
    );

    public function resolvePage($name)
    {
        if ( !isset($this->pages[$name]) ) {
            throw new PageFactoryException(
                'Couldn\'t locate ' . $name,
                PageFactoryException::TYPE_PAGE_CLASS_NOT_FOUND
            );
        }

        return $this->pages[$name];
    }
}
