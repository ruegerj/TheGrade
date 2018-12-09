<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["interface"] . "/IController.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/User.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/HashHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));

    class RegisterController implements IController
    {
        public static function get($params)
        {
            //empty handler
            return null;
        }

        public static function post($params)
        {    
            //conditions for validate       
            $conditions = array(
                "name" => array(
                    "min" => 3,
                    "max" => 30
                ),        
                "prename" => array(
                    "min" => 3,
                    "max" => 30
                ),
                "email" => "/^(([^<>()\[\]\\.,;:\s@']+(\.[^<>()\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/",
                "password" => array(
                    "min" => 8,
                    "max" => 50
                )
            );
            
            //trim user input and encodes it to utf8
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
                //check if data is valid with conditions              
                if ($forgeryTokenValid === true && (strlen($name) >= $conditions["name"]["min"] && strlen($name) <= $conditions["name"]["max"])
                    && (strlen($prename) >= $conditions["prename"]["min"] && strlen($prename) <= $conditions["prename"]["max"])
                    && preg_match($conditions["email"], $email) && (strlen($password) >= $conditions["password"]["min"] && strlen($password) <= $conditions["password"]["max"])
                    && $password === $passwordConfirm) 
                {
                    $hashedPassword = HashHelper::generateHash($password);
                    $dbHelper = new DBHelper();                    
                    $createdUser = $dbHelper->addUser($name, $prename, $email, $hashedPassword); 
                    $sessionHelper->LoginUser($createdUser);

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