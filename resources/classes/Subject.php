<?
    class Subject
    {
        function __construct(int $id, string $title, string $description, float $grading, float $gradeAverage, int $areaId)
        {
            $Id = $id;
            $Title = $title;
            $Description = $description;
            $Grading = $grading;
            $GradeAverage = $gradeAverage;
            $AreaId = $areaId;
        }

        public $Id;
        public $Title;
        public $Description;
        public $Grading; //Factor of the grading of this subject
        public $GradeAverage;
        public $AreaId; // FK Area
    }    
?>