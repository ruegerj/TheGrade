<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));

    class GradeManager {

        /**
         * proceeds all necessary steps to update all averages within the tree (area, subject)
         * @param $subjectId id of subject => is set when subject wich was updated still exists 
         * @param $parentAreaId id of the parent area of the subject => is set when the subject has been deleted
         */
        public static function registerSubjectUpdate(?int $subjectId = null, ?int $parentAreaId = null) : void
        {
            $dbHelper = new DBHelper();
            if (isset($subjectId)) {
                $requestedSubject = $dbHelper->getSubjectById($subjectId);
                GradeManager::updateAreaSubjectAverage($requestedSubject->AreaId);
            } else if (isset($parentAreaId)) {
                GradeManager::updateAreaSubjectAverage($parentAreaId);
            }
        }

        /**
         * proceeds all necessary steps to update all averages within the tree (area, subject) when an exam has changed
         * @param $examId id of exam => is set when exam wichwas updated still exists
         * @param $parentSubjectId id of the parent subject of the exam => is set when the exam has been deleted
         */
        public static function registerExamUpdate(?int $examId = null, ?int $parentSubjectId = null) : void
        {
            $dbHelper = new DBHelper();
            if (isset($examId)) {
                $requestedExam = $dbHelper->getExamById($examId);
                GradeManager::updateSubjectAverage($requestedExam->SubjectId);
                GradeManager::registerSubjectUpdate($requestedExam->SubjectId);
            } else if (isset($parentSubjectId)) {
                GradeManager::updateSubjectAverage($parentSubjectId);
                GradeManager::registerSubjectUpdate($parentSubjectId);
            }
        }


        /**
         * calcs and updates the grade average of a subject
         * @param $subjectId id of subject
         */
        private static function updateSubjectAverage(int $subjectId) : void
        {
            $dbHelper = new DBHelper();
            $examsOfSubject = $dbHelper->getAllExams($subjectId);
            $examCount = $gradeSum = 0;
            foreach ($examsOfSubject as $exam) { //add all the gradings to divide the grade sum with it
                $examCount += $exam->Grading; 
                $gradeSum += ($exam->Grade * $exam->Grading);
            }
            $newAverage = round(($gradeSum / $examCount), 2); //round to 2 decimals
            $dbHelper->updateSubjectGradeAverage($subjectId, $newAverage);
        }

        /**
         * calcs and updates the subject average of an area
         * @param $areaId id of area
         */
        private static function updateAreaSubjectAverage(int $areaId) : void
        {
            $dbHelper = new DBHelper();
            $subjectsOfAreaWithExams = $dbHelper->getAllSubjectsWithExams($areaId);
            $subjectCount = $averageSum = 0;
            foreach ($subjectsOfAreaWithExams as $subject) { //add all the gradings to divide the subject average sum with it
                $subjectCount += $subject->Grading; 
                $averageSum += ($subject->GradeAverage * $subject->Grading);
            }
            $newAverage = round(($averageSum / $subjectCount), 2); //round to 2 decimals
            $dbHelper->updateAreaSubjectAverage($areaId, $newAverage);
        }
    }
?>