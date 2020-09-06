# Aqua
A simple PHP router to create APIs with PDO support.

## Setup
1. Change connection information in [Database.php](Database.php)
2. Set array of HTTP methods in [aqua.php](aqua.php)
3. Change base path in the `__construct()`function of [aqua.php](aqua.php) (if needed)
4. Change the `authenticate();` method in [aqua.php](aqua.php)
5. Change the `postHandler();`method in [aqua.php](aqua.php)

## Usage
1. Include (or require) the aqua file
3. Create a variable for the router ($aqua) and for the database ($db)
```php
$aqua = new Aqua();
$db = $aqua->openConnection();
```
4. Add new routes with the respond method `$aqua->respond(request method, path, authentification, callback);`
    * **Request method** HTTP method that should be responded to. Type: string
    * **Path** Path that will be responded. Type: string. _Use values wrapped in colons (eg. `:id:`) as variables_
    * **Authentication** Should the authentification method be used. Type: bool
    * **Callback** The callback function that should be executed if the path matches. Pass in the arguments stated in the path. _Add a `use ($db)` if you want access to the database_ 
    
## Example
```php
$aqua = new Aqua();
$db = $aqua->openConnection();

$aqua->respond('GET', '/article/:id:', false, function($id) use ($db){
  echo "Requested article with id $id";
});
```
