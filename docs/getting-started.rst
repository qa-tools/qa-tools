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
            "qa-tools/qa-tools": "~1.0",
            "mindplay/annotations": "~1.2@dev"
        }
    }

2. Install/update your vendors:

.. code-block:: bash

    $ curl http://getcomposer.org/installer | php
    $ php composer.phar install

To verify successful installation look for the ``qa-tools/qa-tools`` folder within ``/vendor/`` folder of your project.

Configuration
-------------
The library needs to be properly configured before usage. This is done through the help of ``Config`` class instance:

.. literalinclude:: examples/config_base_url.php
   :linenos:
   :emphasize-lines: 8-10

Typical configuration process consists of 3 steps:

#. obtaining (or creating) Mink's session object (line 7)
#. providing configuration options via ``Config`` class instance (line 8-10)
#. creating ``PageFactory`` class instance with objects created above (line 11)

Then created ``PageFactory`` class instance can be used to spawn Page classes at will.

.. note:: If several Mink's sessions are used, then separate ``PageFactory`` instance needs to be created for each
          of them. It is allowed to share the ``Config`` class instance among them.

.. _configuration-options:

Configuration Options
^^^^^^^^^^^^^^^^^^^^^
The following configuration options are available:

* ``base_url`` - allows to specify Base URL to be used to transform all relative urls from :ref:`@page-url annotations <page-url>` into absolute urls.
* ``page_namespace_prefix`` - array of namespaces in which the :ref:`DefaultPageLocator <page-locator>` will search for page classes defaulted with ``array('\\')``


If port is specified as part of `base_url` then it will be used in every built url unless specified exactly in the `@page-url` annotation.

Connecting to Behat
-------------------
.. note:: Throughout this tutorial it's assumed that working Behat with MinkExtension is configured and connected to a project that needs to be tested.

To use library with Behat you are required to also install https://github.com/qa-tools/behat-extension.

Connecting to PHPUnit
---------------------
.. note:: Throughout this tutorial it's assumed that working PHPUnit is configured and connected to a project that needs to be tested.

To use library with PHPUnit you are required to also install https://github.com/aik099/phpunit-mink.

.. important:: **TODO:** Write about the obtaining Mink session need for PageFactory and that it can be easily done using PHPUnit-Mink.

Connecting PHPUnit-Mink
^^^^^^^^^^^^^^^^^^^^^^^
...

Creating Test Case File
^^^^^^^^^^^^^^^^^^^^^^^
...
