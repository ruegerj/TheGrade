<?
    class Exam
    {
        function __construct(int $id, string $title, string $description, int $date, float $grade, float $grading, int $subjectId)
        {
            $this->Id = $id;
            $this->Title = $title;
            $this->Description = $description;
            $this->Date = $date;
            $this->Grade = $grade;
            $this->Grading = $grading; 
            $this->SubjectId = $subjectId; 
        }

        public $Id;
        public $Title;
        public $Description;
        public $Date;
        public $Grade;
        public $Grading; // Factor of the grading of this exam
        public $SubjectId; // FK Subject
    }
?>