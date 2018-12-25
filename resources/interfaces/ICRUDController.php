<?
    interface ICRUDController 
    {
        public static function add(array $params = array()) : void;

        public static function update(array $params = array()) : void;

        public static function delete(array $params = array()) : void;
    }

?>