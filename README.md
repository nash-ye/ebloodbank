eBloodBank
==========

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nash-ye/ebloodbank/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nash-ye/ebloodbank/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/nash-ye/ebloodbank/badges/build.png?b=master)](https://scrutinizer-ci.com/g/nash-ye/ebloodbank/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/nash-ye/ebloodbank/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

An open-source system that provides a set of must-have features to start an online blood bank.

Feature Highlights
------------------

- **Customizable** The code is nicely organized to ease the customization process as much as possible.

- **Translation Ready** It's ready for translation to your language using GNU gettext.

- **Users Management** It ships with a straightforward users management system which includes, but is not limited to, the authentication, session and role-based-access-control mechanisms.

- **Donors Management** It includes a simple user-interface to add, edit, approve, remove and search by specific criteria.

- **City\District Taxonomy** It provides an ability to categorize the donors by a manageable cities and districts list.

- **Pretty Permalinks** It supports the Pretty Permalinks which make sense and not filled with incomprehensible parameters.

Requirements
------------------

- PHP version 7.2 or higher.
- MySQL version 5.7 or higher.
- Apache HTTP server (with mod_rewrite module) or Nginx.


Installation
------------------

1. Run `composer` and install the system packages.
2. Open `config/config.php.dist` file in a text editor, fill in your information and save it as `config/config.php`.
3. Open `install.php` in your browser and follow up the installer instructions.
4. Login with the user e-mail and password you chose during the installation.


License
------------------

eBloodBank is released under the terms of the GPL version 3 or (at your option) any later version.
