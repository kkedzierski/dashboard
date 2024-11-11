# Documentation for CGRD dashboard

Welcome to the documentation page for the cgrd-dashboard portal!

## Used solutions:
#### Docker:
The applications are run in Docker containers, allowing easy deployment on any operating system.
#### Client side:
##### - vanilla JavaScript, HTML, CSS
##### - [Atomic design](https://bradfrost.com/blog/post/atomic-web-design/)
##### - [Web components](https://developer.mozilla.org/en-US/docs/Web/Web_Components)
The Atomic Design approach has been applied, enabling modular development of web applications.
The client-side application communicates with the API server using a JWT token returned by the server.
It was built in plain JavaScript, without frameworks, which allows for a fast application, designed as a React-like framework imitation.

#### Server side:
##### - PHP 8.0, PHPUnit
The server was also developed in plain PHP, without frameworks, allowing for a fast application.

- Developed using the DDD (Domain-Driven Design) approach
- Implemented migrations
- Unit tests have been implemented
- Implemented API
- Implemented JWT authorization
- Implemented error handling
- Implemented logging

## Technical guide
- [Installation](technicalGuide/installation.md)
- [Running](technicalGuide/running.md)
- [Tests](technicalGuide/tests.md)
- [Api documentation](technicalGuide/api.md)
- [Code documentation](technicalGuide/serverCode)
- [This documentation](technicalGuide/thisDocumentation.md)

### Table of contents
- [Future improvements](futureImprovement.md)
- [Authors](authors.md)
