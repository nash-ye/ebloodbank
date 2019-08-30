### v1.5 (30/8/2019)
- Enhancement: Improve the ACL layer by using Zend ACL component.
- Enhancement: Separate the ACL layer from the Model layer.

### v1.4 (27/8/2019)
- Enhancement: Update code architecture.
- Enhancement: Load jQuery and Bootstrap from jsDelivr CDN.
- Enhancement: Raise PHP and MySQL versions requirement.
- Enhancement: Use JSON column type instead of separated tables. 

### v1.3.3 (8/10/2016)
- Bug Fix: Remove the trailing slash from the base path to fix wrong 404 pages bug.
- Enhancement: Update Composer dependencies.

### v1.3.2 (21/7/2016)
- Bug Fix: Rename the duplicated "B+" blood group to "B-" 
- Enhancement: Update Composer dependencies to the latest versions.

### v1.3.1 (3/3/2016)
- Bug Fix: Fix a bug related to files paths on Windows hosts.
- Enhancement: Update Composer dependencies to the latest versions.

### v1.3 (25/2/2016)
- New Feature: Theming functionality.
- Enhancement: Update jQuery to v2.2.1.
- Enhancement: Use Tahoma and Arial fonts in the Arabic UI.
- New Feature: Add support for Redis, APCu and File System cache.
- Enhancement: Update Composer dependencies to the latest versions.

### v1.2.4
- Bug Fix: Fix some FastCGI compatibility bugs.
- Bug Fix: Use single SQL statements only during the installation.

### v1.2.3
- Enhancement: Add EBB_DB_DRIVER constant to select the database driver manually.

### v1.2.2
- New Feature: Donor Contact Visibility.
- Enhancement: Update Bootstrap to v3.3.6.
- Enhancement: Check if Apache mod_rewrite module is loaded on bootstrapping.
- Enhancement: Add config-sample.php file to make the upgrade process easier.

### v1.2.1
- Bug Fix: Use the default cookies domain to fix the compatibility bugs with RFC 2109.

### v1.2
- New Feature: Add an option to turn on site publication.
- Enhancement: Improve the RBAC (Role Based Access Control) mechanism.
- New Feature: Add a form field to search for a blood group alternatives.
- New Feature: Add an ability to sign up an account and add a donor in the same step.

### v1.1
- New Feature: Add a new page to view single donor details.
- New Feature: Send e-mail notifications after users or donors registration.
- New Feature: Add an ability to bulk delete, activate and approve entities.
- Enhancement: Improve the user experience in managing pending users and donors.
- Enhancement: Keep forms fields values after failed entities addition submissions.
- Bug Fix: Add a check to avoid registering a user with an existing e-mail address.
...and more!

### v1.0.4
- Bug Fix: Add a check to avoid registering a user with an existing e-mail address.

### v1.0.3
- Enhancement: Use the PHP mysqli extension if pdo_mysql extension is not installed.
- Bug Fix: Set custom Doctrine ORM proxies namespace and directory path.
