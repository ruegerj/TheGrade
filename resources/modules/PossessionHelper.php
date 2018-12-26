<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));    

    class PossessionHelper
    {
        /**
         * checks if the current user is the owner of this area
         * @param $areaId id oaf area
         */
        public static function isOwnerOfArea(int $areaId) : bool
        {
            $dbHelper = new DBHelper();
            $userId = PossessionHelper::getCurrentUserId();
            $areasOfUser = $dbHelper->getAllAreas($userId);
            foreach ($areasOfUser as $area) {
                if ($area->Id === $areaId) {
                   return true;
                }
            }
            return false;
        }

        /**
         * checks if the current user is the owner of this subject
         * @param $subjectId id of subject
         */
        public static function isOwnerOfSubject(int $subjectId) : bool
        {
            $dbHelper = new DBHelper();
            $userId = PossessionHelper::getCurrentUserId();
            $requestedSubject = $dbHelper->getSubjectById($subjectId);
            if (isset($requestedSubject)) {
                $areasOfUser = $dbHelper->getAllAreas($userId);
                foreach ($areasOfUser as $area) { //check if subject is atached to an area of the current user
                    if ($area->Id === $requestedSubject->AreaId) {
                        return true;
                    }                    
                }
                return false;
            } else {
                return false; //subject doesnt exist
            }
        }

        /**
         * checks if the current user is the owner of this exam
         * @param $examId id of exam
         */
        public static function isOwnerOfExam(int $examId) : bool
        {
            $dbHelper = new DBHelper();
            $userId = PossessionHelper::getCurrentUserId();
            $requestedExam = $dbHelper->getExamById($examId);
            if (isset($requestedExam)) {
                $areasOfUser = $dbHelper->getAllAreas($userId);
                $subjectsOfUser = array();
                //get all subjects of user to check if exam is atached to an subject of the user
                foreach ($areasOfUser as $area) { 
                    $subjectsOfArea = $dbHelper->getAllSubjects($area->Id);
                    foreach ($subjectsOfArea as $subject) {
                        if ($subject->Id === $requestedExam->SubjectId) {
                            return true;
                        }
                    }
                }    
                return false;            
            } else {
                return false; //exam doesnt exist
            }
        }

        private static function getCurrentUserId() : int
        {
            $sessionHelper = new SessionHelper();
            return $sessionHelper->getSessionData()->UserId;
        }
    }    
?>