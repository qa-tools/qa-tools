Getting Started
===============

...

Installation
------------
The library can be installed easily via Composer.

1. Define the dependencies in your  ``composer.json`` file:

.. code-block:: json

    {
        "require": {
            "qa-tools/qa-tools": "~1.0"
        }
    }

2. Install/update your vendors:

.. code-block:: bash

    $ curl http://getcomposer.org/installer | php
    $ php composer.phar install

To verify successful installation look for the ``qa-tools/qa-tools`` folder within ``/vendor/`` folder of your project.

Configuration
-------------
The library can be optionally configured using following approach:

.. literalinclude:: examples/config_base_url.php
   :linenos:
   :emphasize-lines: 14-16

Then created ``PageFactory`` class instance can be used to spawn ``Page`` class instances at will.

.. note:: If several Mink's sessions are used (e.g. for different browsers), then separate ``PageFactory`` class
          instance needs to be created for each of them. Configuration setting can be shared across different
          ``PageFactory`` class instances, when **same container** is used to create them.

.. _configuration-options:

Configuration Options
^^^^^^^^^^^^^^^^^^^^^
The following configuration options are available:

* ``base_url`` - allows to specify Base URL to be used to transform all relative urls from :ref:`@page-url annotations <page-url>` into absolute urls.
* ``page_namespace_prefix`` - array of namespaces in which the :ref:`DefaultPageLocator <default-page-locator>` will search for page classes defaults to ``array('\\')``


If port is specified as part of `base_url` then it will be used in every built url unless specified explicitly in the `@page-url` annotation.

Connecting to Behat
-------------------
.. note:: Throughout this tutorial it's assumed that working Behat with MinkExtension is configured and connected to a project that needs to be tested.

To use library with Behat you are required to also install https://github.com/qa-tools/behat-extension.

Connecting to PHPUnit
---------------------
.. note:: Throughout this tutorial it's assumed that working PHPUnit is configured and connected to a project that needs to be tested.

To use library with PHPUnit you are required to also install https://github.com/minkphp/phpunit-mink.

.. important:: **TODO:** Write about the obtaining Mink session need for PageFactory and that it can be easily done using PHPUnit-Mink.

Connecting PHPUnit-Mink
^^^^^^^^^^^^^^^^^^^^^^^
...

Creating Test Case File
^^^^^^^^^^^^^^^^^^^^^^^
...
