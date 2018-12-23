<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["interface"] . "/IController.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/TemplateHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/ViewData.php"));


    class AreaController implements IController
    {
        public static function get(array $params = array()) : void
        {            
            $sessionHelper = new SessionHelper();
            $dbHelper = new DBHelper();
            $sessionData = $sessionHelper->getSessionData();
            $areas = $dbHelper->getAllAreas($sessionData->UserId);
            $viewData = new ViewData("Areas",$sessionData, $areas);
            TemplateHelper::renderFileInTemplate("AreaView.php", true, array("data" => $viewData));
        }

        public static function post(array $params = array()) : void
        {

        }
    }

?>