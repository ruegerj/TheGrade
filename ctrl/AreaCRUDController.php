<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["interface"] . "/ICRUDController.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/FormatHelper.php"));

    class AreaCRUDController implements ICRUDController
    {
        public static function add(array $params = array()): void
        {
            $sessionHelper = new SessionHelper();
            $dbHelper = new DBHelper();
            $params = FormatHelper::sanitize($params);    
            $conditionsTitle = $GLOBALS["config"]["validate"]["title"];
            $conditionsDescription = $GLOBALS["config"]["validate"]["description"];        
            extract($params);    
            //validate user input        
            if ($sessionHelper->checkAntiforgeryToken($aftoken) && strlen($title) >= $conditionsTitle["min"]
                && strlen($title) <= $conditionsTitle["max"] && strlen($description) <= $conditionsDescription["max"]) {
                $sessionData = $sessionHelper->getSessionData();
                $dbHelper->addArea($title, $description, $sessionData->UserId);
            }
            header("Location: " . $_SERVER["HTTP_REFERER"]); //redirect to areas page
        }

        public static function update(array $params = array()): void
        {
            $sessionHelper = new SessionHelper();
            $dbHelper = new DBHelper();
            $params = FormatHelper::sanitize($params);
            $conditionsTitle = $GLOBALS["config"]["validate"]["title"];
            $conditionsDescription = $GLOBALS["config"]["validate"]["description"];
            extract($params);
            //validate user input
            if ($sessionHelper->checkAntiforgeryToken($aftoken) && isset($areaId) && $areaId > 0  && strlen($title) >= $conditionsTitle["min"]
                && strlen($title) <= $conditionsTitle["max"] && strlen($description) <= $conditionsDescription["max"]) {
                if (AreaCRUDController::isOwner($areaId)) {
                    $dbHelper->updateArea($areaId, $title, $description);
                }
            }
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }

        public static function delete(array $params = array()): void
        {
            $sessionHelper = new SessionHelper();
            $dbHelper = new DBHelper();
            extract($params);
            //validate user input
            if ($sessionHelper->checkAntiforgeryToken($aftoken) && isset($areaId) && $areaId > 0) {
                if (AreaCRUDController::isOwner($areaId)) { //check if owner
                    $dbHelper->deleteArea($areaId);                   
                }
            }
            header("Location: " . $_SERVER["HTTP_REFERER"]); //redirect to areas page
        }

        //checks if the requested area is owned by the current user
        private static function isOwner(int $requestedAreaId) : bool
        {
            $sessionHelper = new SessionHelper();
            $dbHelper = new DBHelper();
            $sessionData = $sessionHelper->getSessionData();
            $areasOfUser = $dbHelper->getAllAreas($sessionData->UserId);
            foreach ($areasOfUser as $area) {
                if ($area->Id === $requestedAreaId) {
                   return true;
                }
            }
            return false;
        }
    }    
?>