<?
    interface IController 
    {
        function __construct($requestMethod);

        public function render();

        public function getData();

        public function validate();

    }
?>