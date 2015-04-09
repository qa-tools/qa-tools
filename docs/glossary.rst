Glossary
========
.. important:: **TODO:** Explain each term and how they are used to organize overall experience within a library.

Page Element
------------
...

Page Object
-----------
...

Element Proxy
-------------
...

Annotation
----------
...

Page Factory
------------
...


.. _page-locator:

Page Locator
------------

The page locator is used by the page factory to locate/resolve the FQCN defined by a given name.

DefaultPageLocator
~~~~~~~~~~~~~~~~~~

The default page locator uses the following strategy to locate pages:

#. uppercases the first letter of each word and joining them to a CamelCase like class name
#. prepending configured namespaces defined via :ref:`page_namespace_prefix <configuration-options>`
#. returning first existing class

.. code-block:: php

    <?php
    ...
    $page_locator = new DefaultPageLocator(array('\\shop\\pages', '\\shop\\account\\pages'));
    $page_class = $page_locator->resolvePage('Login Page');
    $page = new $page_class($page_factory);
    ...
    ?>

Depending on existence either ``\shop\pages\LoginPage`` or ``\shop\account\pages\LoginPage`` will be returned.

Defining a custom locator
~~~~~~~~~~~~~~~~~~~~~~~~~

In some cases it might be necessary to build a custom page locator. For example to map page names to specific classes.

.. literalinclude:: examples/custom_page_locator.php
    :linenos:

Now it is possible to either locate the page manually by its name

.. code-block:: php

    <?php
    ...
    $page_locator = new MappingPageLocator();
    $registration_page_class = $page_locator->resolvePage('Registration Page');
    $registration_page = new $registration_page_class($page_factory);
    ...
    ?>

or inject the new locator into ``PageFactory`` and use it for initialization.

.. code-block:: php

    <?php
    ...
    $page_factory->setPageLocator(new MappingPageLocator());
    $registration_page = $page_factory->getPage('Registration Page');
    ...
    ?>