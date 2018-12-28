<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/ViewData.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["interface"] . "/IController.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/PossessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/TemplateHelper.php"));

    class ExamController implements IController
    {
        public static function get(array $params = array()): void
        {
            extract($params);
            $subjectId = $id;           
            if (isset($subjectId) && PossessionHelper::isOwnerOfSubject($subjectId)) {
                $sessionHelper = new SessionHelper();
                $dbHelper = new DBHelper();
                $sessionHelper->generateAntiForgeryToken("/subject");
                $sessionData = $sessionHelper->getSessionData();
                $requestedSubject = $dbHelper->getSubjectById($subjectId);
                $parentArea = $dbHelper->getAreaById($requestedSubject->AreaId);
                $exams = $dbHelper->getAllExams($subjectId);
                $viewData = new ViewData($requestedSubject->Title, $sessionData, $exams, 
                array("Areas" => "/areas", $parentArea->Title => "/area?id=" . $parentArea->Id, $requestedSubject->Title => "subject?id=" . $requestedSubject->Id));
                TemplateHelper::renderFileInTemplate("ExamView.php", true, array("data" => $viewData, "subjectId" => $requestedSubject->Id));
            } else {
                header("Location: /"); //redirect to index;
            }
        }

        /**
         * empty handler
         */
        public static function post(array $params = array()): void
        {
            
        }
    }
?>