<?
    include_once(realpath(dirname(__FILE__) . "/resources/classes/Request.php"));
    include_once(realpath(dirname(__FILE__) . "/resources/modules/Router.php"));

    //start router
    $router = new Router(new Request);

    $router->get('/', function ($request) {
        echo "Hello World";
    });

    $router->get('/test', function () {
        echo "Test";
    });

    $router->get('/user', function() {
        header("Location: user.php");
        exit();
    });
?>