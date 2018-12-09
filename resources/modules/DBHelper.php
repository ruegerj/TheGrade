<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/TemplateHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/User.php"));

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
            } catch (Exception $ex) { // connection couldnt be establisht
                TemplateHelper::renderErrorPage("500", "Service unavailable", $ex);
                die();
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
                TemplateHelper::renderErrorPage("500", "Service unavailable", $ex);
            }
        }

        /**
         * gets an user by the id and returns a user object
         */
        public function getUserById($id)
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("SELECT * FROM user WHERE Id = :id");
                $statement->execute(array(":id" => $id));
                $result = $statement->fetchAll()[0];
                $user = new User($result["Id"], $result["Name"], $result["Prename"], $result["Email"], $result["Password"]);
                // while($row = $statement->fetch()) {                    
                //     $user = new User($row["Id"], $row["Name"], $row["Prename"], $row["Email"], $row["Password"]);                    
                // }    
                return $user;

            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "Service unavailable", $ex);
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