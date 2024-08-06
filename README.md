# PHP test

## 1. Installation

  - create an empty database named "phptest" on your MySQL server
  - import the dbdump.sql in the "phptest" database
  - put your MySQL server credentials in the constructor of DB class
  - you can test the demo script in your shell: "php index.php"

## 2. Expectations

This simple application works, but with very old-style monolithic codebase, so do anything you want with it, to make it:

  - easier to work with
  - more maintainable


Bad Practices Identified:
1. Use of require_once for Class Loading:

Why it's bad: Manually including files with require_once leads to boilerplate code and can result in maintenance challenges as the codebase grows.
Solution: Use Composer's autoloading to manage class loading automatically.

2. No Namespaces:

Why it's bad: Without namespaces, classes with the same name can conflict, especially in larger projects or when using third-party libraries.
Solution: Use namespaces to organize code and avoid naming conflicts, making it easier to manage and scale the codebase.

3. No Environment Variables:

Why it's bad: Hardcoding configuration settings (e.g., database credentials) directly in the code makes it difficult to manage configurations across different environments and poses security risks.
Solution: Use environment variables to store configuration settings, allowing for flexible management of different environments (e.g., development, testing, production) and enhancing security by keeping sensitive data out of the source code.