<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));

    class InstallationHelper
    {
        function __construct()
        {
            $this->setUp();
        }

        private function setUp()
        {
            if (DBHelper::checkDBExists() == false) {
                $dbSql = $this->getDBCreationScript();
                $userSql = $this->generateUserScript();
                DBHelper::setUpDB($dbSql, $userSql);
            }
        }

        private function getDBCreationScript()
        {
            $dbName = $GLOBALS["config"]["db"]["dbname"];
            $filePath = $GLOBALS["config"]["paths"]["resources"]["backup"] . "/" . strtolower($dbName) . ".sql";
            if ($backupFile = fopen($filePath, "r")) {
                $content = fread($backupFile, filesize($filePath));
                fclose($backupFile);
                return $content;
            }
        }

        private function generateUserScript()
        {
            $host = $GLOBALS["config"]["db"]["host"];
            $dbName = $GLOBALS["config"]["db"]["dbname"];
            $username = $GLOBALS["config"]["db"]["username"];
            $password = $GLOBALS["config"]["db"]["password"]; 
            $script = "CREATE USER \'" . $username . "\'@\'" . $host . "\' IDENTIFIED VIA mysql_native_password USING \'" . $password ."\';" .
                      "GRANT USAGE ON *.* TO '" . $username . "'@'" . $host . "\'".
                      "REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;" .
                      "GRANT ALL PRIVILEGES ON `" . $dbName ."`.* TO \'" . $username . "\'@\'" . $host . "\';";
            return $script;
        }
    }

?>