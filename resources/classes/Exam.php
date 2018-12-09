<?
    class Exam
    {
        function __construct($id, $title, $description, $grade, $grading, $subjectId)
        {
            $this->$Id = $id;
            $this->$Title = $title;
            $this->$Description = $description;
            $this->$Grade = $grade;
            $this->$Grading = $grading; 
            $this->$SubjectId = $subjectId; 
        }

        public $Id;
        public $Title;
        public $Description;
        public $Grade;
        public $Grading; // Factor of the grading of this exam
        public $SubjectId; // FK Subject
    }
?>