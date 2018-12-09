<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/TemplateHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["interface"] . "/IController.php"));

    class LoginController implements IController
    {
       public static function get($params)
       {
            $sessionHelper = new SessionHelper();
            $token = $sessionHelper->generateAntiForgeryToken();
            TemplateHelper::renderFileInTemplate("LoginView.php", false, array($params, "title" => "Welcome", "afToken" => $token));
       }    

       public static function post($params)
       {

       }
    }
?>