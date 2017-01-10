# Example Challenge **Eduardo Pereira** #

For this challenge, I was asked to develop a lightweight framework in PHP, so I decided to go with the provided example and created a small Personal Library Manager using, my own, custom made, PHP framework

## Some concepts I've taken in consideration: ##

* OOP PHP using PSR-4 (autoloading) and PSR-2 (code style) conventions.
* MVC Software Architectural Pattern.
* An approach do IoC using a Dependency Injection Container.
* Custom ORM (inspired by Active Record pattern).
* Routing system with application single point of entry.

## Infrastructure: ##

**Web Server - EC2**

* Ubuntu 16.04
* Nginx 1.10.0
* Let’s Encrypt SSL
* PHP 7.0.8-0ubuntu0.16.04.3 (FPM)
    - PHP Extension: PHP-7.0-curl
    - PHP Extension: PHP-7.0-mysql (PDO)
* OpenJDK 1.8.0_111
* Elasticsearch 2.3.1

**Amazon RDS**

* MariaDB 10.0.24

## Folder structure: ##

```
+-- docs                                   (Support docs)
+-- web                                    (Main codebase folder)
|   +-- App
|   |   +-- config                         (Application configuration files)
|   |   +-- Lib
|   |   |   +-- Services                   (All the Services for DI)
|   |   |   --- BaseController.php         (A lib where all controllers should extend)
|   |   |   --- BaseModel.php              (A lib where all models should extend)
|   |   |   --- DiContainer.php            (The Dependency Injection Container)
|   |   +-- Mvc
|   |   |   +-- Controllers                (All the controllers)
|   |   |   +-- Models                     (All the models)
|   |   |   +-- Views                      (All the views)
|   |   --- Bootstrap.php                  (As the name says, bootstraps the application)
|   |   --- Routes.php                     (File where all the routes are defined)
|   |   --- Services.php                   (File where set the services into the container)
|   +-- assets                             (Raw sass and JS files – NOT USED)
|   +-- public                             (Public folder on nginx)
|   |   +-- assets                         (Public static assets – images, styles and js)
|   |   --- index.php                      (Single point of entry PHP file)
|   +-- vendor                             (Our composer vendor folder)
|   --- composer.json                      (Our composer project. Used for Autoloading)
--- .gitignore                             (git ingore directives)
--- README.md                              (me =)
```

## Api access: ##

You can use the api endpoint to create book, by calling:

```
curl -X POST -H "Cache-Control: no-cache" -H "Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW" -F "bookcase_id=2" -F "shelf_id=1" -F "isbn=123-4-56-789012-3" -F "title=Jack and Jill" -F "author=Jack and Jill" -F "year=1999" "http://example.eduardopereira.pt/api/books"
```

## Live example: ##

https://example.eduardopereira.pt