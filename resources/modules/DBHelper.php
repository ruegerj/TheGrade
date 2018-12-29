<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/TemplateHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/User.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/Area.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/Subject.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/Exam.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/RememberMeToken.php"));

    class DBHelper 
    {
        private $pdoConnection; 

        function __construct()
        {
            $this->establishConnection();
        }

        //connect to db
        private function establishConnection() : void
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

        /**
         * Checks if the sceified DB from the config exists already
         */
        public static function checkDBExists() : bool
        {            
            try {
                $host = $GLOBALS["config"]["db"]["host"];
                $dbName = $GLOBALS["config"]["db"]["dbname"];     
                $rootUser = $GLOBALS["config"]["db"]["rootUser"];
                $rootPassword = $GLOBALS["config"]["db"]["rootPassword"];           
                $pdoConnection = new PDO("mysql:charsetutf8mb4;host" . $host . ";", $rootUser, $rootPassword); //try connect to server
                $pdoConnection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
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
                die();
            }  
        }

        /**
         * sets up the database and the application-user for the Grade
         * @param $dbSql content of sql backup-script from db
         * @param $userSql content of sql script wich creates the application user
         */
        public static function setUpDB(string $dbSql, string $userSql) : void
        {
            try {
                $host = $GLOBALS["config"]["db"]["host"];
                $rootUser = $GLOBALS["config"]["db"]["rootUser"];
                $rootPassword = $GLOBALS["config"]["db"]["rootPassword"];
                //application-user doesn't exist yet => use root instead
                $pdoConnection = new PDO("mysql:host=" . $host . ";", $rootUser, $rootPassword); 
                $pdoConnection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                $createDBStatement = $pdoConnection->prepare($dbSql); 
                $createDBStatement->execute(); //create db       
                $pdoConnection = null; //close connection
                //create new connection to ensure the context "knows" the created db
                //else the db is "unknown" to the context and the statement would complete with errors => user wont be created
                $pdoConnection = new PDO("mysql:host=" . $host . ";", $rootUser, $rootPassword);   
                $pdoConnection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );              
                $createUserStatement = $pdoConnection->prepare($userSql);
                $createUserStatement->execute();//create user
                $pdoConnection = null; //close connection
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "Service unavailable", $ex->getMessage());
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
        public function addUser(string $name, string $prename, string $email, string $password, int $registrationDate) : User
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("INSERT INTO user (Name, Prename, Email, Password, RegistrationDate) VALUES (:name, :prename, :email, :password, :registrationDate)");
                $statement->execute(array(":name" => $name, ":prename" => $prename, ":email" => $email, ":password" => $password, ":registrationDate" => $registrationDate));   
                return $this->getUserById($newId = $pdo->lastInsertId());            
            } catch (PDOException $ex) {
                TemplateHelper::renderErrorPage("500", "Service unavailable", $ex->getMessage());
                die();
                
            }
        }

        /**
         * gets an user by the id and returns a user object
         * @param $id id of requested user
         */
        public function getUserById(int $id) : ?User
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("SELECT * FROM user WHERE Id = :id");
                $statement->execute(array(":id" => $id));
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                if ($statement->rowCount() > 0) {                    
                    extract($result); // eg. turn $result["name"] into $name 
                    return new User($Id, $Name, $Prename, $Email, $Password, $RegistrationDate);                                 
                } else {
                    return null;
                }
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }    

        /**
         * gets an user by the email and returns an user object
         * @param $email email of requested user
         */
        public function getUserByEmail(string $email) : ?User
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
                die();
            }
        }
        
        /**
         * gets all emails wich equals the given email
         * @param $email email to search
         */
        public function getMatchingEmails(string $email) : array
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
                die();
            }
        }

        /**
         * stores a RememberMeToken in db for the given user
         * @param $userId id of user
         * @param $token RememberMeToken object
         */
        public function storeRememberMeToken(int $userId, RememberMeToken $token) : void
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("INSERT INTO remembermetoken (Creation, Token, PrivateKey, UserId)" .
                "VALUES (:creation, :token, :privateKey, :userId)");
                $statement->execute(array(":creation" => $token->Creation, ":token" => $token->Token, ":privateKey" => $token->PrivateKey, ":userId" => $userId));                
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * gets the currently active rememberMeToken of the given user
         * @param $userId id of user
         */
        public function getActiveRememberMeToken(int $userId) : ?RememberMeToken
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("SELECT * FROM remembermetoken WHERE UserId = :userId ORDER BY Creation DESC LIMIT 1");
                $statement->execute(array(":userId" => $userId));
                if ($statement->rowCount() > 0) {
                    extract($statement->fetch(PDO::FETCH_ASSOC));
                    return new RememberMeToken($Id, $Creation, $Token, $PrivateKey, $userId);                    
                } else {
                    return null;
                }
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * gets all areas of a user
         * @param $userId id of a user
         */
        public function getAllAreas(int $userId) : array
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
                die();
            }

        }

        /**
         * gets an area by the id
         * @param $areaId id of area
         */
        public function getAreaById(int $areaId) : ?Area
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("SELECT * FROM area WHERE Id = :areaId");
                $statement->execute(array(":areaId" => $areaId));
                if ($statement->rowCount() > 0) {
                    extract($statement->fetch(PDO::FETCH_ASSOC));
                    return new Area($Id, $Title, $Description, $SubjectAverage, $UserId);
                } else {
                    return null;
                }
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * adds an area to a user
         * @param $title title of area
         * @param $description description of area
         * @param $userId id of user
         */
        public function addArea(string $title, string $description, int $userId) : void
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("INSERT INTO area (Title, Description, UserId) VALUES (:title, :description, :userId)");
                $statement->execute(array(":title" => $title, ":description" => $description, ":userId" => $userId));
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * updates the data of an area
         * @param $areaId id of the area
         * @param $title new title of the area
         * @param $description new description off the area
         */
        public function updateArea(int $areaId,  string $title, string $description) : void
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("UPDATE area SET Title = :title,  Description = :description WHERE Id = :areaId");
                $statement->execute(array(":title" => $title, ":description" => $description, ":areaId" => $areaId));
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * updates the subject average of an area
         * @param $areaId id of area
         * @param $newAverage new subject average of area
         */
        public function updateAreaSubjectAverage(int $areaId, float $newAverage) : void
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("UPDATE area SET SubjectAverage = :average WHERE Id = :areaId");
                $statement->execute(array(":average" => $newAverage, ":areaId" => $areaId));
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * deletes an area including the atached subjects and their atached exams
         * @param $areaId id of area
         */
        public function deleteArea(int $areaId) : void
        {
            try {
                $pdo = $this->pdoConnection;
                $subjectsOfArea = $this->getAllSubjects($areaId); //get subjects of area
                foreach ($subjectsOfArea as $subject) { //delete all subjects                    
                    $this->deleteSubject($subject->Id);
                }
                $statement = $pdo->prepare("DELETE FROM area WHERE Id = :areaId");
                $statement->execute(array(":areaId" => $areaId)); //delete area
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * gets all subjects of an area
         * @param $areaId id of area
         */
        public function getAllSubjects(int $areaId) : array
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("SELECT subject.Id, subject.Title, subject.Description, subject.Grading, subject.GradeAverage, subject.AreaId FROM area INNER JOIN subject ON area.Id = subject.AreaId WHERE area.Id = :areaId");   
                $statement->execute(array(":areaId" => $areaId));
                $subjects = array();
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    array_push($subjects, new Subject($Id, $Title, $Description, $Grading, $GradeAverage, $AreaId));
                }
                return $subjects;
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * gets a subject by the id
         * @param $subjectId id of subject
         */
        public function getSubjectById(int $subjectId) : ?Subject
        {            
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("SELECT * FROM subject WHERE Id = :subjectId");
                $statement->execute(array(":subjectId" => $subjectId));
                if ($statement->rowCount() > 0) {
                    extract($statement->fetch(PDO::FETCH_ASSOC));
                    return new Subject($Id, $Title, $Description, $Grading, $GradeAverage, $AreaId);
                } else {
                    return null;
                }
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * adds a subject to an area
         * @param $areaId id of parent area
         * @param $title title of subject
         * @param $description description of subject
         * @param $grading of subject as factor
         */
        public function addSubject(int $areaId, string $title, string $description, float $grading) : void
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("INSERT INTO subject (Title, Description, Grading, AreaId) VALUES (:title, :description, :grading, :areaId)");
                $statement->execute(array(":title" => $title, ":description" => $description, ":grading" => $grading, "areaId" => $areaId));
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * updates the data of a subject
         * @param $subjectId id of subject
         * @param $title new title
         * @param $description new description
         * @param $grading new grading
         */
        public function updateSubject(int $subjectId, string $title, string $description, float $grading) : void
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("UPDATE subject SET Title = :title, Description = :description, Grading = :grading WHERE Id = :subjectId");
                $statement->execute(array(":title" => $title, ":description" => $description, ":grading" => $grading, ":subjectId" => $subjectId));
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * updates the grade average of an subject
         * @param $subjectId id of subject
         * @param $newAverage new average of subject
         */
        public function updateSubjectGradeAverage(int $subjectId, float $newAverage) : void
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("UPDATE subject SET GradeAverage = :average WHERE Id = :subjectId");
                $statement->execute(array(":average" => $newAverage, ":subjectId" => $subjectId));
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * deletes an subject including all atached exams
         * @param $subjectId id of subject
         */
        public function deleteSubject(int $subjectId) : void
        {            
            try {
                $pdo = $this->pdoConnection;
                $examsOfSubject = $this->getAllExams($subjectId); //get all exams of subject
                foreach ($examsOfSubject as $exam) { //delete all exams
                    $this->deleteExam($exam->Id);
                }
                $statement = $pdo->prepare("DELETE FROM subject WHERE Id = :subjectId");
                $statement->execute(array(":subjectId" => $subjectId));
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * gets all exams of a subject
         * @param $subject id of subject
         */
        public function getAllExams(int $subjectId) : array
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("SELECT exam.Id, exam.Title, exam.Description, exam.Date, exam.Grade, exam.Grading, exam.SubjectId FROM subject INNER JOIN exam ON subject.Id = exam.SubjectId WHERE subject.Id = :subjectId");
                $statement->execute(array(":subjectId" => $subjectId));
                $exams = array();
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    array_push($exams, new Exam($Id, $Title, $Description, $Date, $Grade, $Grading, $SubjectId));
                }
                return $exams;
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * gets an exam by the id
         * @param $examId id of exam
         */
        public function getExamById(int $examId) : ?Exam
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("SELECT * FROM exam WHERE Id = :examId");
                $statement->execute(array(":examId" => $examId));
                if ($statement->rowCount() > 0) {
                    extract($statement->fetch(PDO::FETCH_ASSOC));
                    return new Exam($Id, $Title, $Description, $Date, $Grade, $Grading, $SubjectId);
                } else {
                    return null;
                }
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * adds an exam to a subject
         * @param $subjectId id of subject
         * @param $title title of exam
         * @param $description description of exam
         * @param $date unix from date of exam
         * @param $grade grade of exam
         * @param $grading grading of exam as factor
         */
        public function addExam(int $subjectId, string $title, string $description, int $date, float $grade, float $grading) : void
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("INSERT INTO exam (Title, Description, Date, Grade, Grading, SubjectId) VALUES (:title, :description, :date, :grade, :grading, :subjectId)");
                $statement->execute(array(":title" => $title, ":description" => $description, ":date" => $date, ":grade" => $grade, ":grading" => $grading, ":subjectId" => $subjectId));
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * updates the data of an exam
         * @param $examId id of exam
         * @param $title title of exam
         * @param $description description of exam
         * @param $date unix from date of exam
         * @param $grade grade of exam
         * @param $grading grading of exam as factor
         */
        public function updateExam(int $examId, string $title, string $description, int $date, float $grade, float $grading) : void
        {
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("UPDATE exam SET Title = :title, Description = :description, Date = :date, Grade = :grade, Grading = :grading WHERE Id = :examId");
                $statement->execute(array(":title" => $title, ":description" => $description, ":date" => $date, ":grade" => $grade, ":grading" => $grading, ":examId" => $examId));
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        /**
         * deletes an exam
         * @param $examId id of exam
         */
        public function deleteExam(int $examId) : void
        {            
            try {
                $pdo = $this->pdoConnection;
                $statement = $pdo->prepare("DELETE FROM exam WHERE Id = :examId");
                $statement->execute(array(":examId" => $examId));
            } catch (Exception $ex) {
                TemplateHelper::renderErrorPage("500", "An error occured", $ex->getMessage());
                die();
            }
        }

        private function closeConnection() : void
        {
            $this->pdoConnection = null; //destroy PDO object => connection will be closed automatically
        }

        function __desctruct()
        {
            $this->closeConnection();
        }
    }
?>