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
    require_once(realpath($config["paths"]["controller"] . "/RegisterController.php"));

    //start session, if needed
    new SessionHelper();
  
    //get db-helper / establish connection
    $dbHelper = new DBHelper(); 

    //start router
    $router = new Router(new Request);    

    //get-handler for index/login page
    $router->get('/', function ($request) {
        $sessionHelper = new SessionHelper();
        if ($sessionHelper->checkLogin()) {
            echo "Login successful";
            echo "<br/>" . $_SESSION["USER_TOKEN"];
            echo "<br/>" . $_SESSION["USER_ID"];
            echo "<br/>" . $_SESSION["USER_NAME"];
            echo "<br/>" . $_SESSION["USER_PRENAME"];
            echo "<br/>" . $_SESSION["USER_EMAIL"];
        } else {
            LoginController::get(array());
        }
    });

    //post-handler for login
    $router->post('/login', function ($request) {
        echo "Logged in";  

    });

    //post-handler for register
    $router->post('/register', function ($request) {
        $params = $request->getBody();        
        RegisterController::post($params);        
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