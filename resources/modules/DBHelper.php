<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/TemplateHelper.php"));

    class DBHelper 
    {
        private $pdoConnection; 

        function __construct()
        {
            $this->establishConnection();
        }

        private function establishConnection()
        {
            $host = $GLOBALS["config"]["db"]["host"];
            $dbName = $GLOBALS["config"]["db"]["dbname"];
            $username = $GLOBALS["config"]["db"]["username"];
            $password = $GLOBALS["config"]["db"]["password"];     
            try {
                $this->pdoConnection = new PDO("mysql:host=". $host .";dbname=". $dbName, $username, $password);
                $this->pdoConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // disable emulation of prepared statements
                $this->pdoConnection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); //enable error output                            
            } catch (PDOException $ex) { // connection couldnt be establisht
                TemplateHelper::renderErrorPage("500", "Service unavailable", $ex);
                die();
            }       
        }

        private function closeConnection()
        {
            $this->pdoConnection = null; //destroy PDO object => connection will be closed automatically
        }

        function __desctruct()
        {
            $this->closeConnection();
        }
    }
?>