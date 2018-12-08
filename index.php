<?
    require_once(realpath(dirname(__FILE__) . "/resources/classes/Request.php"));
    require_once(realpath(dirname(__FILE__) . "/resources/modules/Router.php"));
    require_once(realpath(dirname(__FILE__) . "/ctrl/LoginController.php"));

    $GLOBALS["config"] = $config; // store config in super global array

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