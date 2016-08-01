# Blog Plugin

For cakePHP 1.3

Simple Blog Plugin for CakePHP

## Prerequisites

* An installed instance of cakePHP
* A basic [Authentication system](http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Authentication.html)
* A global user variable :

    ```
    class AppController extends Controller {
      function beforeFilter() {
          $this->user['User'] = $this->Auth->user();
      }
    }
    ```

## Installation

1. Put the content of this plugin in "app/plugins/" in a folder named "blog".
2. Run the following command `cake schema create DbAcl`
3. Run "database.sql" in the database.

## Startup

With the default route, accessing the blog : `blog/blog_posts/index`
