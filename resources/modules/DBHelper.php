<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/TemplateHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/User.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/Area.php"));

    class DBHelper 
    {
        private $pdoConnection; 

        function __construct()
        {
            $this->establishConnection();
        }

        //connect to db
        private function establishConnection()
        {
            $host = $GLOBALS["config"]["db"]["host"];
            $dbName = $GLOBALS["config"]["db"]["dbname"];
            $username = $GLOBALS["config"]["db"]["username"];
            $password = $GLOBALS["config"]["db"]["password"];     
            try {
                $this->pdoConnection = new PDO("mysql:charset=utf8mb4;host=". $host .";dbname=". $dbName, $username, $password);
                $this->pdoConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // disable emulation of prepared statements
                $this->pdoConnection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); //enable error output                            
            } catch (Exception $ex) { // connection couldnt be establisht
                TemplateHelper::renderErrorPage("500", "Service unavailable", $ex->getMessage());
                die();
            }       
        }

        //connect to db-server
        private static function establishConnectionToServer()
        {
            $host = $GLOBALS["config"]["db"]["host"];
            $username = $GLOBALS["config"]["db"]["username"];
            $password = $GLOBALS["config"]["db"]["password"];
            try {
                return new PDO("mysql:charset=utf8mb4;host=" . $host . ";", $username, $password);
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "Service unavailable", $ex->getMessage());
            }
        }

        /**
         * Checks if the sceified DB from the config exists already
         */
        public static function checkDBExists()
        {            
            try {
                $dbName = $GLOBALS["config"]["db"]["dbname"]; //try connect to server
                $pdoConnection = DBHelper::establishConnectionToServer();
                $statement = $pdoConnection->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbName");
                $statement->execute(array(":dbName" => $dbName));
                $dbCount = $statement->rowCount(); //get the count of databases with the same name on the mysql server
                if ($dbCount > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "Service unavailable", $ex->getMessage());
            }  
        }

        /**
         * sets up the database and the application-user for the Grade
         * @param $dbSql content of sql backup-script from db
         * @param $userSql content of sql script wich creates the application user
         */
        public static function setUpDB($dbSql, $userSql)
        {
            try {
                $pdoConnection = DBHelper::establishConnectionToServer();
                $createDbStatement = $pdoConnection->prepare($dbSql);
                $createDbStatement->execute(); //create db
                $createUserStatement = $pdoConnection->prepare($userSql);
                $createUserStatement->execute(); // create user
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "Service unavailable", $ex->getMessage());
            }
        }

        /**
         * Adds user in DB
         * @param $name name of user
         * @param $prename prename of user
         * @param $email email of user
         * @param $password password of user
         */
        public function addUser($name, $prename, $email, $password)
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("INSERT INTO user (Name, Prename, Email, Password) VALUES (:name, :prename, :email, :password)");
                $statement->execute(array(":name" => $name, ":prename" => $prename, ":email" => $email, ":password" => $password));   
                return $this->getUserById($newId = $pdo->lastInsertId());            
            } catch (PDOException $ex) {
                TemplateHelper::renderErrorPage("500", "Service unavailable", $ex->getMessage());
            }
        }

        /**
         * gets an user by the id and returns a user object
         * @param $id id of requested user
         */
        public function getUserById($id)
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("SELECT * FROM user WHERE Id = :id");
                $statement->execute(array(":id" => $id));
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                extract($result); // eg. turn $result["name"] into $name 
                return new User($Id, $Name, $Prename, $Email, $Password);                                 
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
            }
        }    

        /**
         * gets an user by the email and returns an user object
         * @param $email email of requested user
         */
        public function getUserByEmail($email)
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("SELECT * FROM user WHERE Email = :email");
                $statement->execute(array(":email" => $email));
                if ($statement->rowCount() > 0) {
                    $result = $statement->fetch(PDO::FETCH_ASSOC);
                    extract($result); //extract variables from array
                    return new User($Id, $Name, $Prename, $Email, $Password);
                } else {
                    return null;
                }
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
            }
        }
        
        /**
         * gets all emails wich equals the given email
         * @param $email email to search
         */
        public function getMatchingEmails($email)
        {
            try {
                $pdo = $this->pdoConnection;                
                $statement = $pdo->prepare("SELECT Email FROM user WHERE Email = :email");
                $statement->execute(array(":email" => $email));
                $emailsFound = array();
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    extract($row); //extract variables from array
                    array_push($emailsFound, $Email);                    
                }               
                return $emailsFound;
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
            }
        }

        /**
         * gets all areas of a user
         * @param $userId id of a user
         */
        public function getAllAreas($userId)
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("SELECT area.Id, area.Title, area.Description, area.SubjectAverage FROM user INNER JOIN area ON user.Id = area.UserId WHERE user.Id = :userId");
                $statement->execute(array(":userId" => $userId));
                $areas = array();
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    array_push($areas, new Area($Id, $Title, $Description, $SubjectAverage, $userId));
                }
                return $areas;
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
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