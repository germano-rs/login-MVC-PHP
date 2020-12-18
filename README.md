# mvcPHP
This is a repository for an MVC project in PHP. This type of architecture divides the code structure into Models, Views and Controllers in a friendly URL. This way the code is cleaner, organized and easy to maintain.

## To instal:<br>
- download and extract the .zip on your root directory;<br>
- edit the file /Core/Config.php whith your connection information;<br>
- edit the file .htaccess on line 6 replacing "mvc" by the name of your root directory; :warning:

## Creating your first CONTROLLER
- create a new file in /Controllers/new file.php;
- rename the file so that its name ends with Controller as in the example: __homeController.php__ ;<br>
:warning: by default the first letter of the file name must be lowercase and the first letter of "Controller" uppercase;<br>
:computer: _in the __controller__ will be the backend or business rules of your system. It must also make the connection between the data coming from the database (model) and the screen (view)._
- in the controller, create a class whith the same name of the file without the extension like:  class homeController;
- the class needs to extends to __"Controller"__
- the basic structure from your controller is like: <br>
```
class homeController extends Controller
{
    public function index($params)
    {
        $this->loadTemplate('home');
    }
}
```

- by default the homeController/index is index page;
- the controller must be called through the url, passing the name of the desired controller after the domain, like: www.domain.com/home for _homeController_ as a controller;
- the defined Controller method can be called via Url, with the method name after the Controller name, like: www.domain.com/controller/getUser for _getUser_ as a method;
- parameters can be passed to the method called via Url, after the defined method, like:  www.domain.com/controller/method/171 for _171_ as a parameter;
- the data must be passed to the View, or even the View without data, calling the method ```$this->loadTemplate('path/to/the/view', $data);```, passing the data in the form of an array;
## Creating your first MODEL
- create a new file in /Models/new file.php;
- rename the file to the same name as the Controller, but without "Controller" as in the example: __home.php__;<br>
:warning: by default the file name must be lowercase;<br>
:computer: _the __model__ will connect to the database and be responsible for responding with the data to the Controller_;<br>
- in the model, create a class whith the same name of the file without the extension like:  class home;
- the basic structure from your model is like: <br>
```
class users
{
    private $con;

    public function __construct()
    {
        $this->con = Connection::getConnection();
    }

 public function getData()
    {
        $sql = 'select * from tableName';
        $cmd = $this->con->query($sql);
        $data = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
}
 ```
 ## Creating your first VIEW
 - create a new file in /View/new file.php;
 - you can rename the file as you want.;<br>
:warning: It is recommended that the name be related to the Controller. You can even create a folder inside the View folder with a suggestive name and, within it, create the View files.<br>
- with the array name entered in the Controller, the information can be retrieved in the View. Example:<br>
__Controller__<br>
```
$this->loadTemplate('path/to/the/view', $data);
```
__View__<br>
```$data = $this->data;```
