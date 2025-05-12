REST Certain Hamcrest Matchers for PHPUnit
==========================================

This library provides `Hamcrest <https://hamcrest.org>`_ matchers that return `PHPUnit <https://phpunit.de>`_
``Constraint`` instances, allowing the matchers to be used anywhere a PHPUnit constraint is allowed.

To get started, install this package as a development dependency using `Composer <https://getcomposer.org>`_, and read
the :doc:`usage` section for next steps.

.. code-block:: console

   composer require --dev rest-certain/hamcrest-phpunit

.. note::

   This is not an official Hamcrest project and has no affiliation with `hamcrest.org <https://hamcrest.org>`_. The
   *matchers* this library provides are not true Hamcrest matchers in that they do not return Hamcrest ``Matcher``
   instances, as `defined by the Hamcrest project <https://hamcrest.org/JavaHamcrest/tutorial#writing-custom-matchers>`_.
   Instead, the functions in this library return ``Constraint`` instances, for use with PHPUnit.

   Check out the **official** PHP port of Hamcrest Matchers:
   `hamcrest/hamcrest-php <https://packagist.org/packages/hamcrest/hamcrest-php>`_.

.. important::

   This project adheres to a :doc:`conduct`. By participating in this project and its community, you are expected to
   uphold this code.

Contents
--------

.. toctree::

   usage
   matchers
   conduct
   copyright
