<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/HashHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/User.php"));

    class SessionHelper
    {
        private $tokenList = array(
            "user" => "USER_TOKEN",
            "forgery" => "ANTIFORGERY_TOKEN",
            "id" => "USER_ID",
            "name" => "USER_NAME",
            "prename" => "USER_PRENAME",
            "email" => "USER_EMAIL"
        );

        function __construct()
        {
            $this->checkSession();
        }
        /**
         * Starts session when not started yet
         */
        private function checkSession()
        {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        }

        /**
         * checks if the user is logged in => token in session
         */
        public function checkLogin()
        {
            if (isset($_SESSION[$this->tokenList["user"]])) {             
                return true;
            }
            else {
                return false;
            }
        }

        /**
         * creates token for user (logged in) and stores data in session / redirect to index
         * @param $user user-object
         */
        public function LoginUser(User $user)
        {
            $userToken = HashHelper::generateToken(array($user->Id, $user->Name, $user->Prename, $user->Email));
            echo "<br/>" . $user->Id;
            echo "<br/>" . $user->Name;
            echo "<br/>" . $user->Prename;
            echo "<br/>" . $user->Email;
            echo "<br/>" . $user->Password;
            $_SESSION[$this->tokenList["user"]] = $userToken;                        
            $_SESSION[$this->tokenList["id"]] = $user->Id;
            $_SESSION[$this->tokenList["name"]] = $user->Name;            
            $_SESSION[$this->tokenList["prename"]] = $user->Prename;
            $_SESSION[$this->tokenList["email"]] = $user->Email;
            header("Location: /" );            
        }

        /**
         * Generates and stores a AntiForgeryToken in session
         * @param $adress request url (optional)
         */
        public function generateAntiForgeryToken($adress = "undefined")
        {
            $random = mt_rand(1, 100000000);
            $token = HashHelper::generateToken(array($adress, $random));
            $_SESSION[$this->tokenList["forgery"]] = $token;
            return $token;
        }

        /**
         * Checks if the token and the token from the session match
         * @param $token token from view
         */
        public function checkAntiforgeryToken($token)
        {
            $sessionToken = $_SESSION[$this->tokenList["forgery"]];           
            return $token === $sessionToken;
        }
    }

?>