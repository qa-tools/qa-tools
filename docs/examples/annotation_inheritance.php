<?php
use QATools\QATools\PageObject\Element\WebElement;
use QATools\QATools\PageObject\Page;

/**
 * @find-by('id' => 'breadcrumbs')
 * @timeout(2)
 * @element-name('Breadcrumbs Control')
 */
class Breadcrumbs extends WebElement
{

}

class HomePage extends Page
{
    /**
     * @var Breadcrumbs
     */
    protected $breadcrumbsDefault;
}

class AboutPage extends Page
{
    /**
     * @var Breadcrumbs
     * @find-by('css' => 'nav.breadcrumbs')
     * @timeout(3)
     * @element-name('Small Breadcrumbs Control')
     */
    protected $breadcrumbsOverride;
}
