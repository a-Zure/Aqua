# Aqua
A collection of PHP scripts I use for writing APIs (With PDO support).

## Setup
1. Load [bundle.php](bundle.php) (Or the single files)
2. Load any Script as `use Aqua\Script as Script;`

## Usage
### Router
```php
$router = new Router();
$router->respond('GET', '/hello/:var', function($parameters){
    echo 'hello' . $parameters['var'];
});
```
Optional (Before using the `respond` method)
```php
//Symbol used to mark variables in the path
$router->param_symbol = ':';

 //Allowed methods (case insensitive)
$router->allowed_methods = ['get', 'post', 'put', 'delete', 'head']; //Default setting
$router->allowed_methods[] = 'head';

//Add 'Something' to the array returned in the respond() callback. Can replace function() use ()
$router->use('Something', $something);
```

### Database
```php
$database = new Database();
$pdo = $database->connect('host', 'databasename', 'username', 'password') //Optional: 'driver' and 'charset'
```
