<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/TemplateHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/SessionHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/FormatHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["interface"] . "/IController.php"));

    class LoginController implements IController
    {
       public static function get(array $params = array()) : void
       {
            $sessionHelper = new SessionHelper();
            $token = $sessionHelper->generateAntiForgeryToken("/");            
            TemplateHelper::renderFileInTemplate("LoginView.php", false, array("default" => $params, "title" => "Welcome", "afToken" => $token));            
       }    

       public static function post(array $params = array()) : void
       {
            $params = FormatHelper::sanitize($params); //sanitize user input
            $conditions = $GLOBALS["config"]["validate"];
            $emailCondition = $conditions["email"]["pattern"];            
            extract($params); //store array values in variables            
            if (isset($emailLogin) && isset($passwordLogin) && isset($aftoken)) {
                $sessionHelper = new SessionHelper();
                $tokenValid = $sessionHelper->checkAntiforgeryToken($aftoken);
                $emailValid = preg_match($emailCondition, $emailLogin);
                $passwordValid = (strlen($passwordLogin) >= $conditions["password"]["min"] && strlen($passwordLogin) <= $conditions["password"]["max"]);                                
                if ($tokenValid === true && $emailValid && $passwordValid) {
                    $dbHelper = new DBHelper();
                    $user = $dbHelper->getUserByEmail($emailLogin);
                    if (isset($user)) {
                        $passwordMatch = password_verify($passwordLogin, $user->Password);
                        if ($passwordMatch === true) {                                                     
                            $sessionHelper->loginUser($user, $rememberMe);
                        } else {
                            //header("Location: /"); //redirect to index                             
                            LoginController::get(array("email" => $emailLogin));                               
                        }
                    } else {
                        //header("Location: /"); //redirect to index
                        LoginController::get(array("email" => $emailLogin));
                    }
                } else {
                    header("Location: /"); //redirect to index                    
                }                                    
            } else {
                header("Location: /"); //redirect to index
            }            
       }
    }
?>