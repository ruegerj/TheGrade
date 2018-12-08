<?
    require_once(realpath(dirname(__FILE__) . "/resources/config.php"));
    $GLOBALS["config"] = $config; // store config in super global array

    require_once(realpath($config["paths"]["resources"]["class"] . "/Request.php"));
    require_once(realpath($config["paths"]["resources"]["module"] . "/Router.php"));
    require_once(realpath($config["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($config["paths"]["resources"]["module"] . "/DBHelper.php"));
    require_once(realpath($config["paths"]["resources"]["module"] . "/HashHelper.php"));
    require_once(realpath($config["paths"]["resources"]["module"] . "/TemplateHelper.php"));
    require_once(realpath($config["paths"]["controller"] . "/LoginController.php"));

    //get session-helper / start session
    $sessionHelper = new SessionHelper();
  
    //get db-helper / establish connection
    $dbHelper = new DBHelper(); 
           
    //start router
    $router = new Router(new Request);    

    //get-handler for index/login page
    $router->get('/', function ($request) {
        $loginCtrl = new LoginController("GET");
        $loginCtrl->render();
    });

    //post-handler for login
    $router->post('/login', function ($request) {
        echo "Logged in";  
    });

    //post-handler for register
    $router->post('/register', function ($request) {
        $params = $request->getBody();
        foreach ($params as $key => $value) {
            echo (string)$key . "|";
            echo (string)$value . "//";
        }
    });

    $router->get('/test', function ($request) {
        echo "Test";
        $params = $request->getBody();
        foreach ($params as $key => $value) {
            echo (string)$key;
            echo (string)$value;
        }
    });     
?>