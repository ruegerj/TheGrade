<?
    class Subject
    {
        function __construct(int $id, string $title, string $description, float $grading, float $gradeAverage, int $areaId)
        {
            $this->Id = $id;
            $this->Title = $title;
            $this->Description = $description;
            $this->Grading = $grading;
            $this->GradeAverage = $gradeAverage;
            $this->AreaId = $areaId;
        }

        public $Id;
        public $Title;
        public $Description;
        public $Grading; //Factor of the grading of this subject
        public $GradeAverage;
        public $AreaId; // FK Area
    }    
?>