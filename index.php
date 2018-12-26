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
    require_once(realpath($config["paths"]["resources"]["module"] . "/PossessionHelper.php"));
    require_once(realpath($config["paths"]["controller"] . "/LoginController.php"));
    require_once(realpath($config["paths"]["controller"] . "/RegisterController.php"));
    require_once(realpath($config["paths"]["controller"] . "/ApiController.php"));
    require_once(realpath($config["paths"]["controller"] . "/AreaController.php"));
    require_once(realpath($config["paths"]["controller"] . "/AreaCRUDController.php"));
    require_once(realpath($config["paths"]["controller"] . "/SubjectController.php"));
    require_once(realpath($config["paths"]["controller"] . "/SubjectCRUDController.php"));

    //start session, if needed
    new SessionHelper();

    //set up db if necessary
    new InstallationHelper();    

    //start router
    $router = new Router(new Request);    

    //get-handler for index/login page
    $router->get('/', function ($request) {
       authenticate();
       header("Location: /areas");
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

    //get-handler for areas overview site
    $router->get('/areas', function ($request) {
        authenticate();
        AreaController::get();
    });

    //post-handler for adding an area
    $router->post('/area-add', function ($request) {
        authenticate();
        AreaCRUDController::add($request->getBody());
    });

    //post-handler for editing area
    $router->post('/area-edit', function ($request) {
        authenticate();
        AreaCRUDController::update($request->getBody());
    });

    //post-handler for deleting an area
    $router->post('/area-del', function ($request) {
        authenticate();
        AreaCRUDController::delete($request->getBody());
    });

    //get-handler for the detail page of an area => with all subjects of area
    $router->get('/area', function ($request) {
        authenticate();
        extract($request->getBody()); //get params
        if (isset($id) && $id > 0) {
            SubjectController::get(array("areaId" => $id));
        } else {
            header("Location: /area"); //redirect to area overview
        }
    });

    //post-handler for adding a subject
    $router->post('/subject-add', function ($request) {
        authenticate();
        SubjectCRUDController::add($request->getBody());
    });

    //post-handler for editing a subject
    $router->post('/subject-edit', function ($request) {
        authenticate();
        SubjectCRUDController::update($request->getBody());
    });

    //post-handler for deleting a subject
    $router->post('/subject-del', function ($request) {
        authenticate();
        SubjectCRUDController::delete($request->getBody());
    });

    //post handler for api calls to checkmail
    $router->post('/api/checkmail', function ($request) {
        $params = $request->getBody();
        ApiController::checkEmailAvailable($params["email"]);        
    });

    //checks if the current user is logged in
    //else the default login page will be rendered
    function authenticate()
    {
        $sessionHelper = new SessionHelper();
        if (!$sessionHelper->checkLogin()) {
            LoginController::get();
            die();
        }
    }
?>