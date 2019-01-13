<?
    /**
     * Defines the required structure for a controller wich can handle get and post requests
     */
    interface IController 
    {
        public static function get(array $params = array()) : void;

        public static function post(array $params = array()) : void;
    }
?>