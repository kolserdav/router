The simple route module.

_Installation_  
~$`composer require kolserdav/router`  
[Make catalog /Controller ...  
Copy file /Controller/TestController.php ...  
Copy file /Controller/ErrorPage.php ...  
Rewrite namespaces ...  
Make catalog /config/route ...  
Copy file /config/route/routes.yaml]  
or call...  
~$`php vendor/kolserdav/router/install`  

_Using_

You must use a single access point.  

index.php
```php
require 'vendor/autoload.php';

use Avir\Router\Route;

$router = new Route();
$router->route();
```
Add your routes in /config/route/routes.yaml  

```yaml
index :                                           
      path : /                                    
      controller: IndexController::indexPublic    
users :
      path : /users/
      controller : User\UserConroller::usersPublic
```

Create custom controllers with methods.  
_For example:_  
IndexController::indexPublic   
User\UserConroller::usersPublic  

When coinciding field 'path' with URI, the specified controller will be turned on.
If URI contains of number, it will be available in the controller as...
```php
$this->id; 
```  
It's all... Very simple!




