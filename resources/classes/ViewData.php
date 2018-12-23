<?
    class ViewData
    {
        function __construct(string $title, Session $sessionObj, array $data, array $crumbs = null)
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