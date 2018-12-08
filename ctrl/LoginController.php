<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/TemplateHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["interface"] . "/IController.php"));

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

        public function getData()
        {
            return array();
        }

        public function validate()
        {
            return false;
        }
    }
?>