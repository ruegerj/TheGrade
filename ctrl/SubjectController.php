<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/ViewData.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["interface"] . "/IController.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/PossessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/TemplateHelper.php"));

    class SubjectController implements IController
    {
        public static function get(array $params = array()): void
        {
            extract($params);
            if (PossessionHelper::isOwnerOfArea($areaId)) {
                $sessionHelper = new SessionHelper();
                $dbHelper = new DBHelper();
                $sessionHelper->generateAntiForgeryToken("/area");
                $sessionData = $sessionHelper->getSessionData();
                $requestedArea = $dbHelper->getAreaById($areaId);                                 
                $subjects = $dbHelper->getAllSubjects($areaId);
                $viewData = new ViewData($requestedArea->Title, $sessionData, $subjects, 
                array("Areas" => "/areas", $requestedArea->Title => "/area?id=" . $requestedArea->Id));
                TemplateHelper::renderFileInTemplate("SubjectView.php", true, array("data" => $viewData, "areaId" => $areaId));            
            } else {
                header("Location: /areas"); //redirect to area overview
            }
        }

        public static function post(array $params = array()): void
        {
            
        }
    }
?>