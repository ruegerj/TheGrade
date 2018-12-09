<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/TemplateHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["interface"] . "/IController.php"));

    class LoginController implements IController
    {
       public static function get($params)
       {
            $sessionHelper = new SessionHelper();
            $token = $sessionHelper->generateAntiForgeryToken();
            TemplateHelper::renderFileInTemplate("LoginView.php", false, array($params, "title" => "Welcome", "afToken" => $token));            
       }    

       public static function post($params)
       {
            $conditions = array(
                "email" => "/^(([^<>()\[\]\\.,;:\s@']+(\.[^<>()\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/",
                "password" => array(
                    "min" => 8,
                    "max" => 50
                )
            );

            $emailCondition = $conditions["email"];
            extract($params); //store array values in variables
            if (isset($emailLogin) && isset($passwordLogin) && isset($aftoken)) {
                $sessionHelper = new SessionHelper();
                $tokenValid = $sessionHelper->checkAntiforgeryToken($aftoken);
                $emailValid = preg_match($emailCondition, $emailLogin);
                $passwordValid = (strlen($passwordLogin) >= $conditions["password"]["min"] && strlen($passwordLogin) <= $conditions["password"]["max"]);
                if ($tokenValid === true) {
                    if ($emailValid && $passwordValid) {
                        $dbHelper = new DBHelper();
                        $user = $dbHelper->getUserByEmail($emailLogin);
                        if (isset($user)) {
                            $passwordMatch = password_verify($passwordLogin, $user->Password);
                            if ($passwordMatch === true) {
                                $sessionHelper->LoginUser($user);
                            } else {
                                header("Location: /"); //redirect to index                                
                            }
                        } else {
                            header("Location: /"); //redirect to index
                        }
                    } else {
                        header("Location: /"); //redirect to index
                    }                    
                } else {
                    header("Location: /"); //redirect to index to generate a new af-token                 
                }
            } else {
                header("Location: /"); //redirect to index
            }            
       }
    }
?>