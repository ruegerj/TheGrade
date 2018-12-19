<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/HashHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/User.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/Session.php"));

    class SessionHelper
    {       
        private $loginStateTime = 30; //time (in minutes) for wich user is logged in without any activity

        function __construct()
        {
            $this->startSession(); // start session if required
            $this->checkLastActivity(); // check last activity of user
            $this->registerActivity(); // register activity
        }
        /**
         * Starts session when not started yet          
         */
        private function startSession() : void
        {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }            
        }

        /**
         * checks if login-state is valid  (time since last activity)
         * logs user out when login-state is invalid (time is overdone)
         */
        private function checkLastActivity() : void
        {
            if (isset($_SESSION[$GLOBALS["config"]["session"]["activity"]])) {
                $sessionUnix = $_SESSION[$GLOBALS["config"]["session"]["activity"]];
                $currentUnix = time();                                
                //check if unix from session + $loginStateTime is in the past
                if ((($sessionUnix += ($this->loginStateTime * 60)) <= $currentUnix )) {
                    $this->LogoutUser();                  
                    die();
                }           
            } else {
                $this->registerActivity();
            }
        }

        /**
         * registers an site-request and stores the time of the request in session
         * => changes login-state
         */
        private function registerActivity() : void
        {
            $_SESSION[$GLOBALS["config"]["session"]["activity"]] = time();
        }

        /**
         * checks if the user is logged in => token in session
         */
        public function checkLogin() : bool
        {
            if (isset($_SESSION[$GLOBALS["config"]["session"]["user"]])) {             
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
        public function loginUser(User $user) : void
        {
            $userToken = HashHelper::generateToken(array($user->Id, $user->Name, $user->Prename, $user->Email));            
            $_SESSION[$GLOBALS["config"]["session"]["user"]] = $userToken;                        
            $_SESSION[$GLOBALS["config"]["session"]["id"]] = $user->Id;
            $_SESSION[$GLOBALS["config"]["session"]["name"]] = $user->Name;            
            $_SESSION[$GLOBALS["config"]["session"]["prename"]] = $user->Prename;
            $_SESSION[$GLOBALS["config"]["session"]["email"]] = $user->Email;
            header("Location: /"); //redirect to index            
        }

        /**
         * Destroys the session completely
         */
        public function logoutUser() : void
        {
            $_SESSION = array(); // emtpy session
            if (ini_get("session.use_cookies")) {
                //destroy cookie
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            //destroy session
            session_destroy();                        
            header("Location: /"); //redirect to index
        }        

        /**
         * Generates and stores a AntiForgeryToken in session
         * @param $adress request url (optional)
         */
        public function generateAntiForgeryToken($adress = "undefined") : string
        {
            $random = mt_rand(1, 100000000);
            $token = HashHelper::generateToken(array($adress, $random));
            $_SESSION[$GLOBALS["config"]["session"]["forgery"]] = $token;
            return $token;
        }

        /**
         * Checks if the token and the token from the session match
         * @param $token token from view
         */
        public function checkAntiforgeryToken($token) : bool
        {
            $sessionToken = $_SESSION[$GLOBALS["config"]["session"]["forgery"]];           
            return $token === $sessionToken;
        }

        /**
         * gets the session-data and returns it as object
         */
        public function getSessionData() : Session
        {
            $sessionData = new Session();
            $sessionData->UserToken = $_SESSION[$GLOBALS["config"]["session"]["user"]];
            $sessionData->AntiForgeryToken = $_SESSION[$GLOBALS["config"]["session"]["forgery"]];
            $sessionData->UserId = $_SESSION[$GLOBALS["config"]["session"]["id"]];
            $sessionData->UserName = $_SESSION[$GLOBALS["config"]["session"]["name"]];
            $sessionData->UserPrename = $_SESSION[$GLOBALS["config"]["session"]["prename"]];
            $sessionData->UserEmail = $_SESSION[$GLOBALS["config"]["session"]["email"]];
            $sessionData->LastActivity = $_SESSION[$GLOBALS["config"]["session"]["activity"]];

            return $sessionData;
        }
    }

?>