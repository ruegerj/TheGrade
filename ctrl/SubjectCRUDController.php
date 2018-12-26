<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["interface"] . "/ICRUDController.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/FormatHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/PossessionHelper.php"));

    class SubjectCRUDController implements ICRUDController
    {
        public static function add(array $params = array()): void
        {
            $sessionHelper = new SessionHelper();
            $dbHelper = new DBHelper();
            $params = FormatHelper::sanitize($params);    
            $conditionsTitle = $GLOBALS["config"]["validate"]["title"];
            $conditionsDescription = $GLOBALS["config"]["validate"]["description"];
            $conditionsGrading = $GLOBALS["config"]["validate"]["grading"];
            extract($params);
            //validate user input
            if (isset($areaId) && PossessionHelper::isOwnerOfArea($areaId)) {
                if ($sessionHelper->checkAntiforgeryToken($aftoken) && isset($title) && strlen($title) > $conditionsTitle["min"] && strlen($title <= $conditionsTitle["max"])
                    && strlen($description) <= $conditionsDescription["max"] && isset($grading) && $grading > $conditionsGrading["min"] && $grading <= $conditionsGrading["max"]) {
                    $dbHelper->addSubject($areaId, $title, $description, $grading / 100);                    
                }               
            }
            header("Location: " . $_SERVER["HTTP_REFERER"]); //redirect to subjects page
        }

        public static function update(array $params = array()): void
        {
            $sessionHelper = new SessionHelper();
            $dbHelper = new DBHelper();
            $params = FormatHelper::sanitize($params);    
            $conditionsTitle = $GLOBALS["config"]["validate"]["title"];
            $conditionsDescription = $GLOBALS["config"]["validate"]["description"];
            $conditionsGrading = $GLOBALS["config"]["validate"]["grading"];
            extract($params);
            //validate user input
            if (isset($areaId) && PossessionHelper::isOwnerOfArea($areaId) && isset($subjectId) && PossessionHelper::isOwnerOfSubject($subjectId)) {
                if ($sessionHelper->checkAntiforgeryToken($aftoken) && isset($title) && strlen($title) > $conditionsTitle["min"] && strlen($title <= $conditionsTitle["max"])
                    && strlen($description) <= $conditionsDescription["max"] && isset($grading) && $grading > $conditionsGrading["min"] && $grading <= $conditionsGrading["max"]) {
                        $dbHelper->updateSubject($subjectId, $title, $description, $grading / 100);
                }
            }
            header("Location: " . $_SERVER["HTTP_REFERER"]); //redirect to subjects page
        }

        public static function delete(array $params = array()): void
        {
            $sessionHelper = new SessionHelper();
            $dbHelper = new DBHelper();
            extract($params);
            //validate user input            
            if (isset($areaId) && PossessionHelper::isOwnerOfArea($areaId) && isset($subjectId) && PossessionHelper::isOwnerOfSubject($subjectId) 
                && isset($aftoken) && $sessionHelper->checkAntiforgeryToken($aftoken)) {  
                    $dbHelper->deleteSubject($subjectId);             
            }
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
    }
?>