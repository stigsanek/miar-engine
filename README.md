# Miar Engine
The foundation on which the best [MVC](https://en.wikipedia.org/wiki/Model–view–controller) web applications are built. Nothing extra. Only vanilla PHP.
The standard template contains the home page, user profile and admin panel.

* [Setup](#Setup)
* [Create page](#Create-page)
* [Routing](#Routing)
* [Controllers](#Controllers)
* [Models](#Models)
* [Views](#Views)
* [Forms](#Forms)
* [Configuration](#Configuration)

## Setup

### Technical Requirements

* Install PHP 7.2.0 or higher.
* [Install Composer](https://getcomposer.org/download/), which is used to install PHP packages.

### Setting

* Clone this git repository.
* Set your database connection settings in the `config/database.php` file.
* Migrate data from file `migration/fixtures.sql` to your database.
* In the console cd to folder of your project and run `composer install`.

### Running Application

* In the console cd to folder `public` of your project and run `php -S 127.0.0.1:8000`.
* Open [http://localhost:8000](http://localhost:8000) in your browser.

## Create page

Creating a new page - whether it’s an HTML page or a JSON endpoint - is a two-step process:

1. **Create a route:** A route is the URL (e.g. `/about`) to your page and points to a controller.
2. **Create a controller:** A controller is the PHP function you write that builds the page. You take the incoming request information and use it to create a response, which can hold HTML content or JSON string.

Suppose you want to create a page - `/randomnumber` - that generates a random number and prints it. To do that, create a `RandomController` class and a `index` method inside of it:

```php
<?php

namespace App\Controllers;

class RandomController extends BaseController
{
    public function index()
    {
        $number = random_int(0, 100);

        echo '<html><body>Random number: ' . $number . '</body></html>';
    }
}
```

Now you need to associate this controller function with a public URL (e.g. `/randomnumber`) so that the `index()` method is called when a user browses to it. This association is defined by creating a route in the `config/routes.php` file:

```php
<?php

use App\Controllers\RandomController;

return [
    'randomnumber' => [RandomController::class, 'index', false]
];
```

Go to: [http://localhost:8000/randomnumber](http://localhost:8000/randomnumber). If you see a random number being printed back to you, congratulations!

## Routing

Component `App\Components\Router` is responsible for routing. When your application receives a request, it calls a controller action to generate the response. The routing configuration defines which action to run for each incoming URL. It also provides other useful features, like generating SEO-friendly URLs (e.g. `/read/intro-to-miar-engine` instead of `index.php?article_id=57`).

Each route consists of 4 required components:

1. Route URL. 
2. Controller class.
3. Controller method.
4. User authorization check flag (`false` by default).

**It is important to remember that the main page route must be located at the very end of the route array.**

```php
<?php

use App\Controllers\MainController;
use App\Controllers\UserController;

return [
    'login' => [UserController::class, 'login', false],
    'logout' => [UserController::class, 'logout', true],

    'index' => [MainController::class, 'index', false],
    '' => [MainController::class, 'index', false]
];
```

The previous examples defined routes whose URL never changes (for example, `/articles`). However, routes usually define where some parts are variable. For example, a URL to display a specific blog post (for example, `/articles/14`).
To do this, you can include a variable in the route configuration, the value of which will be determined by a regular expression.

```php
<?php

use App\Controllers\ArticleController;

return [
    'articles/([0-9]+)' => [ArticleController::class, 'index/$1', false]
];
```

## Controllers

The controller is objects of classes inherited from `App\Controller\BaseController`, responsible for processing the request and generating the response. The response could be HTML page, JSON, file upload, redirect, 404 error, or whatever.
`BaseController` contains helper methods. 

### Redirecting

If you want to redirect the user to another page, use the `redirect()` method:

```php
public function index()
{
    ...

    return $this->redirect('/index');
}
```

### Rendering Views

If you’re serving HTML, you’ll want to render a template. The `render()` method renders a template and puts that content into a response:

```php
public function index()
{
    ...

    $this->view->render('article/index', ['articles' => $model->findAll()], true);
}
```

If you are building API you need to display JSON. The `renderJson()` method returns JSON view:

```php
public function index()
{
    ...

    $this->renderJson(['data' => $data], 200);
}
```

### Managing Errors

* When things are not found, you should return a 404 response:

```php
public function index($id)
{   
    $model = new Article();
    $item = $model->findItemById($id);

    if (empty($item)) {
        $this->actionError(404);
    }

    ...
}
```

* You can also control the request method for a specific route. For example, only allow `GET`:

```php
public function index()
{   
    if (!$this->request->isGet()) {
        $this->actionError(405);
    }

    ...
}
```

### Managing the Session

Miar Engine provides a session service `App\Components\Session` that you can use to store information about the user between requests.

#### Flash Messages

You can store special messages, called "flash" messages, on the user’s session. By design, flash messages are meant to be used exactly once: they vanish from the session automatically as soon as you retrieve them. This feature makes "flash" messages particularly great for storing user notifications.

```php
public function index()
{   
    $this->userSession->setAlert('info', 'Welcome!');

    ...
}
```

In the view page (or better yet, in your basic layout template), read any flash messages from the session using the `getAlerts()` method of the user object.

```php
<?php $alerts = $user->getAlert(); ?>
<?php if (isset($alerts)) : ?>
    <?php foreach ($alerts as $alert) : ?>
        <p><?= $alert['type']; ?></p>
        <p><?= $alert['message']; ?></p>
    <?php endforeach; ?>
<?php endif; ?>
```

#### Arbitrary Data

You can also save arbitrary data and delete it when needed.

```php
public function index()
{   
    $this->userSession->setData('numbers', [1, 2, 3]);

    ...
}

public function item()
{   
    $data = $this->userSession->getData('numbers');
    $this->userSession->resetData('numbers');

    ...
}
```

### The Request Object

Miar Engine provides a request object to any controller method. With it you can get request parameters:

```php
public function index()
{   
    // GET by key
    $getParam = $this->request->getParam('date');

    // POST by key
    $postParam = $this->request->getPostParam('date');

    ...
}
```

## Models

Models are objects of business data, rules, and logic.
You can create model classes by extending the `App\Models\BaseModel` class.
The model class needs to set the property to `table`, which corresponds to the name of the table in the database.
Miar Engine uses [PDO](https://www.php.net/manual/en/book.pdo.php) to work with the database, so in your application you can use any type (after activating the extension in `php.ini`): MySQL, PostgreSQL, MS SQL Server.
To execute a query, you need to call `execQuery()` method of the `BaseModel`, passing it a query string and an array with parameters as arguments.

```php
<?php

namespace App\Models;

class Article extends BaseModel
{
    protected $table = 'article';

    public function findItemById($id)
    {   
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE id = :id'
        $result = $this->execQuery($sql, ['id' => $id]);

        return $result->fetch();
    }
}
```

On every database request, `BaseModel` sets the query execution flag, so you can check the success of the query using property `isError` and get error information using `getErrors()` method in your controllers.

```php
public function security()
{   
    ...

    if (!$this->userModel->isError) {
        $this->userSession->setAlert('success', 'Operation completed successfully');
        $this->redirect('/logout');
    } else {
        $this->userSession->setAlert(
            'danger',
            'Error: ' . implode(', ', $this->userModel->getErrors())
        );
        http_response_code(400);
    }

    ...
}
```

## Views

The view is responsible for presenting data to end users. This PHP scripts that mainly contain HTML code and PHP code that is responsible for appearance. Views are managed by the Application `App\Componenets\View` component, which contains a rendering method for the view.

### Parameters

You can pass any parameters to the view by specifying the name of the variable used in the view in an associative array:

```php
// Yor Controller
public function index()
{   
    ...

    $this->view->render('layout/layout', ['text' => 'Hello, World!'], true);
}

// Your View
<body>
    <p><?= $text; ?></p>
</body>
```

### Nested Rendering

You can use nested rendering with multiple views. The last parameter of the `render()` method is responsible for the final rendering of the page to the user.

```php
public function index()
{
    ...

    $context = $this->view->render('main/index');

    $this->view->render('layout/layout', [
        'title' => 'Home',
        'tab' => 'main',
        'content' => $context
    ], true);
}
```

### Partial View

Repeating parts of views can be placed in a separate file and included in pages. 

```php
<?php include_once  ROOT . '/views/layout/_head.php'; ?>

<body>
  ...
  <?php include_once  ROOT . '/views/layout/_script.php'; ?>
</body>
```

## Forms

Miar Engine provides class `App\Forms\BaseForm` that makes it easy to validate your forms.
When the form is submitted, a check is performed according to the specified rules. An error message is added for fields that fail validation.

### Ussage

You can create form classes by extending the `BaseForm` class.

* Define field names and submit buttons.
* Configure validation rules.

```php
<?php

namespace App\Forms;

class YourForm extends BaseForm
{
    public $name = 'send_data';

    public $dataFields = ['first_name', 'last_name', 'age'];

    protected $rules = [
        ['required', ['first_name', 'age']],
        ['numeric', ['age']]
    ];
}
```

* Fill in the form with the data in controller:

```php
public function login()
{   
    ...

    $form = new YourForm();

    if ($this->request->isSubmitted($form->name)) {
        $this->request->loadData($form);

        if ($form->isReady()) {
            $model->createData($form->getFormData());
        } else {
            http_response_code(400);
        }
    }

    $this->view->render('main/index', [
        'errors' => $this->setFormErrorAlert($form->getErrors())
    ], true);
}
```

* Create a form in your view:

```php
<form method="POST">
    <div>
        <input type="text" name="first_name">
        <?php if (isset($errors['first_name'])) : ?>
            <p><?= $errors['first_name']; ?></p>
        <?php endif; ?>
    </div>
    <div>
        <input type="text" name="last_name">
    </div>
    <div>
        <input type="text" name="age">
        <?php if (isset($errors['age'])) : ?>
            <p><?= $errors['age']; ?></p>
        <?php endif; ?>
    </div>
    <button type="submit" name="send_data">Send</button>
</form>
```

### Validation

`BaseForm` contains basic validation methods, but you can easily create your own rules right in your form class:

```php
protected $rules = [
    ['char', ['new_password', 'new_password_repeat']]
];

protected function runCharValidator(array $fields)
{
    foreach ($fields as $field) {
        if (!preg_match('/(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])/', $this->formData[$field])) {
            $this->errors[$field] = 'Password value does not meet requirements';
        }
    }
}
```

## Configuration

Basic application settings are set in the front controller `public/index.php`.
File `config\.env.php` contains the error display mode parameter. You can include your parameters here and create any logic with them in `public\index.php`.

