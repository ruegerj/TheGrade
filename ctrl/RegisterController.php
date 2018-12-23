<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["interface"] . "/IController.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/User.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/HashHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));

    class RegisterController implements IController
    {
        public static function get(array $params = array()) : void
        {            
            //emtpy handler
        }

        public static function post(array $params = array()) : void
        {    
            $conditions = $GLOBALS["config"]["validate"]; //get validate conditions from config
            
            //trim user input
            foreach ($params as $key => $value) {
                $params[$key] = stripslashes(trim($value));
            }
            
            $aftoken = $params["aftoken"];
            $name = $params["name"];
            $prename = $params["prename"];
            $email = $params["email"];
            $password = $params["password"];
            $passwordConfirm = $params["passwordConfirm"];

            //check if all params from form exist
            if (isset($aftoken) && isset($name) && isset($prename) && isset($email) && isset($password) && isset($passwordConfirm)) {
                $sessionHelper = new SessionHelper();
                $forgeryTokenValid = $sessionHelper->checkAntiforgeryToken($aftoken);  
                $dbHelper = new DBHelper();
                $matchingMailsCount = count($dbHelper->getMatchingEmails($email));
                //check if data is valid with conditions              
                if ($forgeryTokenValid === true && (strlen($name) >= $conditions["name"]["min"] && strlen($name) <= $conditions["name"]["max"])
                    && (strlen($prename) >= $conditions["prename"]["min"] && strlen($prename) <= $conditions["prename"]["max"])
                    && (preg_match($conditions["email"]["pattern"], $email) && $matchingMailsCount <= 0) && (strlen($password) >= $conditions["password"]["min"] 
                    && strlen($password) <= $conditions["password"]["max"]) && $password === $passwordConfirm) 
                {
                    $hashedPassword = HashHelper::generateHash($password);
                    $dbHelper = new DBHelper();                    
                    $createdUser = $dbHelper->addUser($name, $prename, $email, $hashedPassword); 
                    $sessionHelper->loginUser($createdUser);

                } else {
                    header("Location: " . $_SERVER["HTTP_REFERER"]);                    
                }                
            }
            else {                
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }
    }
?>