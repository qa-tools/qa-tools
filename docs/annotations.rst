Annotations
===========
Annotations are widely used to provide access to the library's functionality without a need to write any code.

@find-by
--------
This annotation is used to specify means, by which elements can be found on a page. For example:

.. literalinclude:: examples/annotation_inheritance.php
   :linenos:
   :emphasize-lines: 6,10,17-19,27

When defining an element (line 10) it's possible to specify default ``@find-by`` annotation (line 6), that will be used when
none was specified on a page property (line 17-19). If ``@find-by`` annotation was specified on a page property (line 27),
then it will override any default annotation, that might have been specified on the element's class.

.. note:: Apart then in page classes it's also possible to have properties with ``@find-by`` annotations on element
          sub-classes, that allow presence of sub-elements (e.g. ``AbstractElementContainer``).

Locating elements by Class Name
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: ruby

    @find-by('className' => 'example-class')
    @find-by('how' => 'className', 'using' => 'example-class')

The above annotations would find **any elements**, which contains class ``example-class`` among classes assigned to them. For example:

.. code-block:: html

    <div class="example-class"></div>
    <div class="class-a example-class"></div>
    <div class="example-class class-b"></div>
    <div class="class-a example-class class-b"></div>

Locating elements by CSS Selector
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: ruby

    @find-by('css' => '#test > input.example[type="hidden"]')
    @find-by('how' => 'css', 'using' => '#test > input.example[type="hidden"]')

The above annotations would find elements, which matches ``#test > input.example[type="hidden"]`` CSS selector. For example:

.. code-block:: html

    <input type="hidden" class="example"/>

Locating elements by ID Attribute
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: ruby

    @find-by('id' => 'test')
    @find-by('how' => 'id', 'using' => 'test')

The above annotations would find **any elements** which ``id`` attribute matches ``test``. For example:

.. code-block:: html

    <div id="test"></div>
    <input id="test"/>
    <label id="test"></label>

Locating elements by NAME Attribute
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: ruby

    @find-by('name' => 'test')
    @find-by('how' => 'name', 'using' => 'test')

The above annotations would find **any elements** which ``name`` attribute matches ``test``. For example:

.. code-block:: html

    <input name="test"/>
    <select name="test"></select>

Locating elements by Tag Name
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: ruby

    @find-by('tagName' => 'section')
    @find-by('how' => 'tagName', 'using' => 'section')

The above annotations would find **any elements** which tag name matches ``section``. For example:

.. code-block:: html

    <section name="first">test</section>
    <section name="second">test</section>
    <section></section>

Locating links by Full Text
^^^^^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: ruby

    @find-by('linkText' => 'the link')
    @find-by('how' => 'linkText', 'using' => 'the link')

The above annotations would find **any links** (the ``A`` tag with ``href`` attribute) which visible text (not including
HTML markup) is ``the link``. For example:

.. code-block:: html

    <a href="#">the link</a>
    <a href="http://www.google.com">the link</a>
    <a href="http://www.google.com">the <strong>link</strong></a>

Locating links by Partial Text
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: ruby

    @find-by('partialLinkText' => 'the link')
    @find-by('how' => 'partialLinkText', 'using' => 'the link')

The above annotations would find **any links** (the ``A`` tag with ``href`` attribute) which visible text (not including
HTML markup) contains ``the link``. For example:

.. code-block:: html

    <a href="#">the link</a>
    <a href="http://www.google.com">this is the link</a>
    <a href="http://www.google.com">the <strong>link</strong> is here</a>

Locating elements by XPATH
^^^^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: ruby

    @find-by('xpath' => 'a[@href = "#"]')
    @find-by('how' => 'xpath', 'using' => 'a[@href = "#"]')

The above annotations would find ``A`` tags which ``href`` attribute value matches ``#``. For example:

.. code-block:: html

    <a href="#">the link</a>
    <a href="#">the link is here</a>

.. _page-url:

@page-url
---------
This annotation is used to specify URL associated with each page class. For example:

.. literalinclude:: examples/page_url_simple.php
   :linenos:
   :emphasize-lines: 5

Annotation can have 3 parameters:

#. ``url`` - specifies absolute or relative url to the page (mandatory)
#. ``params`` - specifies additional url parameters in associative array format (defaults to ``array()``)
#. ``secure`` - specifies if secure connection (the ``https://`` protocol) needs to be used (defaults to ``null``)

.. warning:: If some/all parameter names are omitted (as seen in above example), then parameter order must be preserved.

Below is an example of annotation, where ``url`` parameter name is omitted, but ``secure`` parameter name is not:

.. code-block:: ruby

    @page-url('http://www.example.com/products/shoes.html', 'secure' => false)

Parameters in the URL
^^^^^^^^^^^^^^^^^^^^^
Parameters in the url can be specified using 3 ways:

* after question mark (``?``) in ``url`` annotation parameter (first)
* in ``params`` annotation parameter via associative array (second)
* as a combination of both methods from above

All of the following annotations will produce exactly same url:

.. code-block:: ruby

    @page-url('http://www.example.com/products/shoes.html?color=red&size=42')
    @page-url('http://www.example.com/products/shoes.html', array('color' => 'red', 'size' => 42))
    @page-url('http://www.example.com/products/shoes.html?color=red', array('size' => 42))

Same annotations can also be written in long form:

.. code-block:: ruby

    @page-url('url' => 'http://www.example.com/products/shoes.html?color=red&size=42')
    @page-url('url' => 'http://www.example.com/products/shoes.html', 'params' => array('color' => 'red', 'size' => 42))
    @page-url('url' => 'http://www.example.com/products/shoes.html?color=red', 'params' => array('size' => 42))

.. note:: If same url parameter is specified in both ``url`` and ``params`` annotation parameters, then it's value
          from ``params`` takes precedence.

Relative URLs
^^^^^^^^^^^^^
Specifying absolute urls in each of created Page classes introduces fair amount of code duplication. This becomes even
larger problem, when need arises to use Page classes in parallel on 2+ different domains (e.g. local development environment
and Continuous Integration server).

After setting ``base_url`` :ref:`configuration option <configuration-options>` it will be possible to use relative urls
**along side** (can use both at same time for different pages) with absolute ones:

.. code-block:: ruby

    @page-url('products/shoes.html?color=red&size=42')
    @page-url('products/shoes.html', array('color' => 'red', 'size' => 42))
    @page-url('products/shoes.html?color=red', array('size' => 42))

    @page-url('url' => 'products/shoes.html?color=red&size=42')
    @page-url('url' => 'products/shoes.html', 'params' => array('color' => 'red', 'size' => 42))
    @page-url('url' => 'products/shoes.html?color=red', 'params' => array('size' => 42))

Secure/unsecure URLs
^^^^^^^^^^^^^^^^^^^^
If ``secure`` annotation parameter not specified, then protocol from following sources is used:

* for absolute urls: the ``url`` parameter of ``@page-url`` annotation
* for relative urls: ``base_url`` configuration option

By specifying ``secure`` annotation parameter (3rd) value it's possible to force secure/unsecure connection. For example:

.. code-block:: ruby

    // force secure:
    @page-url('products/shoes.html', true)
    @page-url('url' => 'products/shoes.html', 'secure' => true)
    @page-url('url' => 'http://www.example.com/products/shoes.html', 'secure' => true)

    // force non-secure:
    @page-url('products/shoes.html', false)
    @page-url('url' => 'products/shoes.html', 'secure' => false)
    @page-url('url' => 'https://www.example.com/products/shoes.html', 'secure' => false)

Custom ports
^^^^^^^^^^^^
Like for `base_url` it is possible to include a port in an absolute URL:

.. code-block:: ruby

    @page-url('http://www.example.com:8080/products/shoes.html')

URL parameter unmasking
^^^^^^^^^^^^^^^^^^^^^^^
It is possible to make ``url`` parameter of ``@page-url`` annotation more dynamic (currently it's pretty static)
though usage of url masks. The ``url mask`` is a query string parameter name wrapped within ``{`` and ``}`` like so:
``{parameter_name}``. When a query string parameter is encountered in the url in such a form, then instead of being
added to the query string of built url it would be unmasked (substituted) in the main url part itself.

.. code-block:: ruby

    @page-url('products/{product-name}.html', 'params' => array('product-name' => 'shoes'))

It doesn't look too powerful right now, but considering that params would be supplied later in ``Page::open`` method call
it would be a major time saver for SEO url building.

.. note:: Every part of url, except anchor and query string itself can be unmasked in such a way.

@timeout
--------
This annotation is used to specify maximum waiting time (in seconds), after which search attempt for an element, that
is absent on a page, will be considered as `failure` and exception will the thrown.

.. note:: When ``@timeout`` annotation is not specified, then search attempt will be considered as `failure` immediately
          after element won't be found on a page.

For example:

.. literalinclude:: examples/annotation_inheritance.php
   :linenos:
   :emphasize-lines: 7,10,17-19,28

When defining an element (line 10) it's possible to specify default ``@timeout`` annotation (line 7), that will be used when
none was specified on a page property (line 17-19). If ``@timeout`` annotation was specified on a page property (line 28),
then it will override any default annotation, that might have been specified on the element's class.

.. note:: This annotation can be particularly useful, when dealing with AJAX requests in which element in question would be only
          present on a page after AJAX request is over.

@element-name
-------------
This annotation is used to specify human readable name (or semantic meaning) for a particular usage of the element. Later
name, which is set with the help of this annotation will be used in all error messages related to particular element usage
on that page.

For example:

.. literalinclude:: examples/annotation_inheritance.php
   :linenos:
   :emphasize-lines: 8,10,17-19,29

When defining an element (line 10) it's possible to specify default ``@element-name`` annotation (line 8), that will be used when
none was specified on a page property (line 17-19). If ``@element-name`` annotation was specified on a page property (line 29),
then it will override any default annotation, that might have been specified on the element's class.

.. note:: If element name is not specified, then page property name, e.g. ``HomePage::$breadcrumbsDefault``, would be
          used instead.

@bem
----
...
