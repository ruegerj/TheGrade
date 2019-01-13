<?
    /**
     * Represents an Area from the DB in the application
     */
    class Area
    {
        function __construct(int $id, string $title, string $description, float $subjectAverage, int $userId)
        {            
            $this->Id = $id;
            $this->Title = $title;
            $this->Description = $description;
            $this->SubjectAverage = $subjectAverage;
            $this->UserId = $userId;
        }

        public $Id;
        public $Title;
        public $Description;
        public $SubjectAverage;
        public $UserId; //FK User
    }
?>