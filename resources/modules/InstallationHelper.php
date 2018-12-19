<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));

    class InstallationHelper
    {
        function __construct()
        {
            $this->setUp();
        }

        //sets up, if its necessary, the db and the application-user
        private function setUp() : void
        {
            if (DBHelper::checkDBExists() == false) {
                $dbSql = $this->getDBCreationScript();
                $userSql = $this->generateUserScript();
                DBHelper::setUpDB($dbSql, $userSql);
            }
        }

        //gets the sql-code from the generated backup-script of the db
        private function getDBCreationScript() : string
        {
            $dbName = $GLOBALS["config"]["db"]["dbname"];
            $filePath = $GLOBALS["config"]["paths"]["resources"]["backup"] . "/" . strtolower($dbName) . ".sql";
            if ($backupFile = fopen($filePath, "r")) {
                $content = fread($backupFile, filesize($filePath));
                fclose($backupFile);
                return $content;
            }
        }

        //generates based on the data from the config the sql-syntax to create the application user
        private function generateUserScript() : string
        {
            $host = $GLOBALS["config"]["db"]["host"];
            $dbName = $GLOBALS["config"]["db"]["dbname"];
            $username = $GLOBALS["config"]["db"]["username"];
            $password = $GLOBALS["config"]["db"]["password"]; 
            $script = "USE " . $dbName . "; CREATE USER '" . $username . "'@'" . $host . "' IDENTIFIED BY '" . $password ."';" .
                      "GRANT USAGE ON *.* TO '" . $username . "'@'" . $host . "'".
                      "REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;" .
                      "GRANT ALL PRIVILEGES ON `" . $dbName ."`.* TO '" . $username . "'@'" . $host . "';";
            return $script;
        }
    }

?>