<?
    class ViewData
    {
        function __construct($title, $sessionObj, $data, $crumbs = null)
        {
            $this->Title = $title;
            $this->SessionData = $sessionObj;
            $this->Data = $data;
            $this->Crumbs = $crumbs;
        }

        public $Title;
        public $SessionData;
        public $Data;  
        public $Crumbs;      
    }
?>