<?
    class Area
    {
        function __construct($id, $title, $description, $subjectAverage, $userId)
        {
            $this->$Id = $id;
            $this->$Title = $title;
            $this->$Description = $description;
            $this->$SubjectAverage = $subjectAverage;
            $this->$UserId = $userId;
        }

        public $Id;
        public $Title;
        public $Description;
        public $SubjectAverage;
        public $UserId; //FK User
    }
?>