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

4. Lack of Error Handling:
Why it's bad: Without error handling, unexpected failures can crash the application, and diagnosing issues becomes difficult.
Solution: Implement try-catch blocks around database operations to catch and handle exceptions gracefully.

5. Lack of Object-Oriented Principles:

Why it's bad: Procedural code does not leverage the benefits of OOP, such as encapsulation, abstraction, and inheritance, which can lead to harder-to-maintain code.
Solution: Convert the procedural code into an object-oriented structure, using classes to encapsulate functionality and improve maintainability.

6. Lack of Type Declarations:
Why it's bad: Omitting type declarations for method parameters and return types makes the code less readable and prone to type-related errors.
Solution: Use type hints for method parameters and return types to improve clarity and enforce type safety.

