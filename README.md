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

7. Inconsistent Naming Conventions:
Why it's bad: Inconsistent naming conventions for variables and methods (e.g., $n for a News object) make the code less readable and harder to understand.
Solution: Use descriptive and consistent naming conventions for variables and methods throughout the codebase.

8. No Input Validation:
Why it's bad: Without input validation, the application might accept invalid or malicious data, leading to potential security vulnerabilities and errors.
Solution: Validate and sanitize all user inputs before processing them.

9. SQL Injection Vulnerabilities:
Why it's bad: Directly embedding user input into SQL queries makes the application vulnerable to SQL injection attacks, allowing attackers to execute arbitrary SQL code.
Solution: Use prepared statements and parameter binding to safely handle user input in SQL queries.

10. No Transaction Management for Multi-step Database Operations:
Why it's bad: Multi-step database operations (like deleting news and its associated comments) should be handled within transactions to ensure data integrity.
Solution: Use transactions to ensure that a sequence of operations is executed completely or not at all.

11. Inefficient Data Retrieval and Processing:
Why it's bad: The listComments method retrieves all comments and filters them in PHP, leading to unnecessary data processing and inefficiencies.
Solution: Use optimized SQL queries to fetch only the relevant data from the database.