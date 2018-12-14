<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["interface"] . "/IController.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/TemplateHelper.php"));


    class AreaController implements IController
    {
        public static function get($params = array())
        {
            $sessionHelper = new SessionHelper();
            $dbHelper = new DBHelper();
            $sessionData = $sessionHelper->getSessionData();
            $areas = $dbHelper->getAllAreas($sessionData->UserId);
            TemplateHelper::renderFileInTemplate("AreaView.php", true, array(
                "title" => "Areas", "sessionData" => $sessionData, "areas" => $areas));
        }

        public static function post($params = array())
        {

        }
    }

?>