<?
    require_once(realpath($_SERVER["DOCUMENT_ROOT"] . "/resources/config.php"));
    require_once(realpath(MODULES_PATH . "/TemplateHelper.php"));
    require_once(realpath($_SERVER["DOCUMENT_ROOT"] . "/resources/classes/IController.php"));

    class LoginController implements IController
    {
        private $method;

        function __construct($requestMethod) 
        {
            $this->method = $requestMethod;
        }

        public function render() 
        {
            renderFileInTemplate("LoginView.php", false, array("title" => "Welcome"));
        }
    }


        
?>