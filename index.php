<?
    require_once(realpath($_SERVER["DOCUMENT_ROOT"] . "/resources/config.php"));
    $GLOBALS["config"] = $config; // store config in super global array

    require_once(realpath($config["paths"]["resources"]["class"] . "/Request.php"));
    require_once(realpath($config["paths"]["resources"]["module"] . "/Router.php"));
    require_once(realpath($config["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($config["paths"]["resources"]["module"] . "/DBHelper.php"));
    require_once(realpath($config["paths"]["resources"]["module"] . "/HashHelper.php"));
    require_once(realpath($config["paths"]["resources"]["module"] . "/TemplateHelper.php"));
    require_once(realpath($config["paths"]["resources"]["module"] . "/InstallationHelper.php"));
    require_once(realpath($config["paths"]["controller"] . "/LoginController.php"));
    require_once(realpath($config["paths"]["controller"] . "/RegisterController.php"));
    require_once(realpath($config["paths"]["controller"] . "/ApiController.php"));
    require_once(realpath($config["paths"]["controller"] . "/AreaController.php"));

    //start session, if needed
    new SessionHelper();

    //set up db if necessary
    new InstallationHelper();    

    //start router
    $router = new Router(new Request);    

    //get-handler for index/login page
    $router->get('/', function ($request) {
        $sessionHelper = new SessionHelper();
        if ($sessionHelper->checkLogin()) {
            header("Location: /areas");
        } else {
            LoginController::get(array());
        }
    });

    //post-handler for login
    $router->post('/login', function ($request) {
        $params = $request->getBody();        
        LoginController::post($params);
    });

    //get-handler for logout
    $router->get('/logout', function ($request) {
        $sessionHelper = new SessionHelper();
        $sessionHelper->logoutUser();
    });

    //post-handler for register
    $router->post('/register', function ($request) {
        $params = $request->getBody();        
        RegisterController::post($params);        
    });

    //get-handler for areas site
    $router->get('/areas', function ($request) {
        $sessionHelper = new SessionHelper();
        if ($sessionHelper->checkLogin()) {
            AreaController::get();
        } else {
            header("Location: /");
        }
    });

    //post handler for api calls to checkmail
    $router->post('/api/checkmail', function ($request) {
        $params = $request->getBody();
        ApiController::checkEmailAvailable($params["email"]);        
    });
?>