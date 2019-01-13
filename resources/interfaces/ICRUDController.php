<?
    /**
     * Defines the structure for a controller wich can handle CRUD-operations
     */
    interface ICRUDController 
    {
        public static function add(array $params = array()) : void;

        public static function update(array $params = array()) : void;

        public static function delete(array $params = array()) : void;
    }

?>