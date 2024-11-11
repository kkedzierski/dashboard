## API documentation

> **How to Add a New Route?**

To add a new route, go to the **server** folder and edit the `App\Kernel\Router\RegisterRouteManager` file. In the registerRoutes method, add a new entry in the following format:
```php
 $this->router->registerRoute('method', 'path', 'controller class' 'controller method');
```

Example:

```php
 $this->router->registerRoute('POST', '/login', LoginController::class, 'login');
```

> **What Routes Are Currently Available?**
>
```php
POST api/v1/login
params: email, password

Function:
Logs in the user and returns a JWT token or an error.

GET api/v1/auth
Checks if the user is logged in.

GET api/v1/news-posts
Returns all news posts.

POST api/v1/news-posts
params: title, content

Function:
Adds a new news post.

PATCH api/v1/news-posts
params: id, title, content

Function:
Updates a news post.

DELETE api/v1/news-posts
params: id

Function:
Deletes a news post.

```
