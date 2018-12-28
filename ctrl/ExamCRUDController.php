<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["interface"] . "/ICRUDController.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/FormatHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/PossessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/GradeManager.php"));

    class ExamCRUDController implements ICRUDController
    {
        public static function add(array $params = array()): void
        {
            $sessionHelper = new SessionHelper();
            $dbHelper = new DBHelper();
            $params = FormatHelper::sanitize($params);
            $conditionsTitle = $GLOBALS["config"]["validate"]["title"];
            $conditionsDescription = $GLOBALS["config"]["validate"]["description"];
            $conditionsGrading = $GLOBALS["config"]["validate"]["grading"];
            $conditionsGrade = $GLOBALS["config"]["validate"]["grade"];
            $conditionsYear = $GLOBALS["config"]["validate"]["date"]["year"];
            extract($params);
            //validate user input
            if (isset($subjectId) && PossessionHelper::isOwnerOfSubject($subjectId)) {
                if (isset($aftoken) && $sessionHelper->checkAntiforgeryToken($aftoken) && isset($title) && strlen($title) > $conditionsTitle["min"] && strlen($title <= $conditionsTitle["max"])
                && strlen($description) <= $conditionsDescription["max"] && isset($grading) && $grading > $conditionsGrading["min"] && $grading <= $conditionsGrading["max"]
                && isset($grade) && $grade >= $conditionsGrade["min"] && $grade <= $conditionsGrade["max"] && isset($date) && ExamCRUDController::isValidDate($date, $conditionsYear["min"])) {
                    $dbHelper->addExam($subjectId, $title, $description, DateTime::createFromFormat("d.m.Y", $date)->getTimestamp(), $grade, $grading / 100);
                    GradeManager::registerExamUpdate(null, $subjectId);
                } 
            }
            header("Location: /subject?id=" . $subjectId); //redirect to last page
        }

        public static function update(array $params = array()): void
        {
            $sessionHelper = new SessionHelper();
            $dbHelper = new DBHelper();
            $params = FormatHelper::sanitize($params);
            $conditionsTitle = $GLOBALS["config"]["validate"]["title"];
            $conditionsDescription = $GLOBALS["config"]["validate"]["description"];
            $conditionsGrading = $GLOBALS["config"]["validate"]["grading"];
            $conditionsGrade = $GLOBALS["config"]["validate"]["grade"];
            $conditionsYear = $GLOBALS["config"]["validate"]["date"]["year"];
            extract($params);
            //validate user input
            if (isset($subjectId) && PossessionHelper::isOwnerOfSubject($subjectId) && isset($examId) && PossessionHelper::isOwnerOfExam($examId)) {
                if (isset($aftoken) && $sessionHelper->checkAntiforgeryToken($aftoken) && isset($title) && strlen($title) > $conditionsTitle["min"] && strlen($title <= $conditionsTitle["max"])
                && strlen($description) <= $conditionsDescription["max"] && isset($grading) && $grading > $conditionsGrading["min"] && $grading <= $conditionsGrading["max"]
                && isset($grade) && $grade >= $conditionsGrade["min"] && $grade <= $conditionsGrade["max"] && isset($date) && ExamCRUDController::isValidDate($date, $conditionsYear["min"])) {
                    $dbHelper->updateExam($examId, $title, $description, DateTime::createFromFormat("d.m.Y", $date)->getTimestamp(), $grade, $grading / 100);
                    GradeManager::registerExamUpdate($examId);
                }
            }
            header("Location: /subject?id=" . $subjectId); //redirect to last page
        }

        public static function delete(array $params = array()): void
        {
            $sessionHelper = new SessionHelper();
            $dbHelper = new DBHelper();
            extract($params);
            //validate user input
            if (isset($subjectId) && PossessionHelper::isOwnerOfSubject($subjectId) && isset($examId) && PossessionHelper::isOwnerOfExam($examId) && isset($aftoken)
            && $sessionHelper->checkAntiforgeryToken($aftoken)) {
                $dbHelper->deleteExam($examId);
                GradeManager::registerExamUpdate(null, $subjectId);
            }
            header("Location: /subject?id=" . $subjectId); //redirect to last page
        }

        //checks if the date from the user is valid
        private static function isValidDate(string $dateInput, int $minYear) : bool
        {
            $dateParts = explode(".", $dateInput);
            if (count($dateParts) === 3)  {
               foreach ($dateParts as $part) {
                   if ((int)$part === null) {
                       return false;
                   }
               }
               if (checkdate($dateParts[1], $dateParts[0], $dateParts[2])) {
                   $date = DateTime::createFromFormat("d.m.Y", $dateInput);
                   $dateMax = new DateTime();
                   $dateMin = DateTime::createFromFormat("d.m.Y", "0.0." . $minYear);
                   if ($date->getTimestamp() >= $dateMin->getTimestamp() && $date->getTimestamp() <= $dateMax->getTimestamp()) {
                        return true;
                   }
               }
            }
            return false;
        }
    }
?>