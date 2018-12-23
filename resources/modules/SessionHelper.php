<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/HashHelper.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/User.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["class"] . "/Session.php"));
    require_once(realpath($GLOBALS["config"]["paths"]["lib"]["crypto"] . "/CryptoLib.php"));

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
         * checks if the user is logged in => token in session or rememberMe-cookie is valid
         */
        public function checkLogin() : bool
        {
            if (isset($_SESSION[$GLOBALS["config"]["session"]["user"]])) {             
                return true;
            } else if ($this->checkCookieExists($GLOBALS["config"]["cookie"]["remember"])) {             
                $cookie = $_COOKIE[$GLOBALS["config"]["cookie"]["remember"]];                
                $valid = $this->validateRememberMeCookie($cookie);
                if ($valid) {
                    //get user data and login user
                    $userId = base64_decode(explode(":", $cookie)[0]); //get userId from cookie
                    $dbHelper = new DBHelper();
                    $user = $dbHelper->getUserById($userId);
                    $this->loginUser($user);
                } else {
                    $this->destroyRememberMeCookie(); //destroy invalid cookie
                    return false;
                }
            }
            else {
                return false;
            }
        }

        /**
         * creates token for user (logged in) and stores data in session / redirect to index
         * @param $user user-object
         * @param $createCookie should a remember me cookie be generated
         */
        public function loginUser(User $user, bool $createCookie = null) : void
        {
            $userToken = HashHelper::generateToken(array($user->Id, $user->Name, $user->Prename, $user->Email));            
            $_SESSION[$GLOBALS["config"]["session"]["user"]] = $userToken;                        
            $_SESSION[$GLOBALS["config"]["session"]["id"]] = $user->Id;
            $_SESSION[$GLOBALS["config"]["session"]["name"]] = $user->Name;            
            $_SESSION[$GLOBALS["config"]["session"]["prename"]] = $user->Prename;
            $_SESSION[$GLOBALS["config"]["session"]["email"]] = $user->Email;                                                
            if ($createCookie == TRUE && !$this->checkCookieExists($GLOBALS["config"]["cookie"]["remember"])) {    
                $this->generateRememberMeCookie();                                             
            }
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
            $this->destroyRememberMeCookie(); //destroy remember-me cookie            
            //destroy session
            session_destroy();                        
            header("Location: /"); //redirect to index
        }        

        /**
         * Generates and stores a AntiForgeryToken in session
         * @param $adress request url (optional)
         */
        public function generateAntiForgeryToken(string $adress = "undefined") : string
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
        public function checkAntiforgeryToken(string $token) : bool
        {
            $sessionToken = $_SESSION[$GLOBALS["config"]["session"]["forgery"]];           
            return $token === $sessionToken;
        }

        /**
         * Checks if a with the given key exists
         * @param $cookieKey key of cookie to check
         */
        public function checkCookieExists(string $cookieKey) : bool
        {            
            return isset($_COOKIE[$cookieKey]);                  
        }

        /**
         * generates and sets a rememberMe-cookie and stores the information in the db
         */
        public function generateRememberMeCookie() : void
        {
            $sessionData = $this->getSessionData(); //get session data
            $userId = $sessionData->UserId;
            $userIdBase64 = base64_encode((string) $userId);
            $cookieContent = $userIdBase64;
            $userToken = $sessionData->UserToken; //use token from user in session
            $cookieContent .= ":" . $userToken;
            $privateKey = HashHelper::generatePrivateKey(); //generate private key for mac
            $mac = hash_hmac("sha256", $cookieContent, $privateKey); // generate control hash
            $cookieContent .= ":" . $mac;
            $cookieKey = $GLOBALS["config"]["cookie"]["remember"];
            $expireSpan = $GLOBALS["config"]["validate"]["rememberMeCookie"]["timespan"];                     
            setcookie($cookieKey, $cookieContent, time() + (60*60*24*$expireSpan), "/"); //set cookie
            //store cookie information in db
            $dbHelper = new DBHelper();
            $dbHelper->storeRememberMeToken($userId, new RememberMeToken(0, time(), $userToken, $privateKey, $userId));        
        }

        /**
         * validates the rememberMe-cookie and checks if its valid
         * @param $cookie rememberMe-cookie
         */
        public function validateRememberMeCookie(string $cookie) : bool
        {            
            $dbHelper = new DBHelper();
            list($userIdBase64, $token, $mac) = explode(":", $cookie);
            $userIdCookie = base64_decode($userIdBase64);
            $cookieDataFromDb = $dbHelper->getActiveRememberMeToken($userIdCookie);                      
            if ($cookieDataFromDb != null) {
                //use hash_equals to prevent timing attacks
                if (!hash_equals(hash_hmac("sha256", $userIdBase64 . ":" . $token, $cookieDataFromDb->PrivateKey), $mac)) {
                    return false; //control hashes doesnt match so the cookie is invalid
                } 
                if (hash_equals($token, $cookieDataFromDb->Token)) {
                    $expiredSpan = $GLOBALS["config"]["validate"]["rememberMeCookie"]["timespan"];
                    $cookieCreation = new DateTime($cookieDataFromDb->Creation);
                    $expired = $cookieCreation->modify("+" . $expiredSpan ." day") < new DateTime();
                    if ($expired) {
                        return false; //token exists but is expired so its invalid
                    } else {
                        return true; //tokens match the cookie and its not expired so its valid                        
                    }
                }
            }
            return false; //the cookie was never created so its forged
        }

        /**
         * destroys the remember-me cookie if it exists
         */
        public function destroyRememberMeCookie() : void
        {
            if ($this->checkCookieExists($GLOBALS["config"]["cookie"]["remember"])) {
                setcookie($GLOBALS["config"]["cookie"]["remember"], "", time() - 1000);
            }
        }

        /**
         * gets the session-data and returns it as object
         */
        public function getSessionData() : Session
        {
            $sessionData = new Session();
            $sessionData->UserToken = isset($_SESSION[$GLOBALS["config"]["session"]["user"]]) ? $_SESSION[$GLOBALS["config"]["session"]["user"]] : "";
            $sessionData->AntiForgeryToken = isset($_SESSION[$GLOBALS["config"]["session"]["forgery"]]) ? $_SESSION[$GLOBALS["config"]["session"]["forgery"]] : "";
            $sessionData->UserId = isset($_SESSION[$GLOBALS["config"]["session"]["id"]]) ? $_SESSION[$GLOBALS["config"]["session"]["id"]] : "";
            $sessionData->UserName = isset($_SESSION[$GLOBALS["config"]["session"]["name"]]) ? $_SESSION[$GLOBALS["config"]["session"]["name"]] : "";
            $sessionData->UserPrename = isset($_SESSION[$GLOBALS["config"]["session"]["prename"]]) ? $_SESSION[$GLOBALS["config"]["session"]["prename"]] : "";
            $sessionData->UserEmail = isset($_SESSION[$GLOBALS["config"]["session"]["email"]]) ? $_SESSION[$GLOBALS["config"]["session"]["email"]] : "";
            $sessionData->LastActivity = isset($_SESSION[$GLOBALS["config"]["session"]["activity"]]) ? $_SESSION[$GLOBALS["config"]["session"]["activity"]] : "";

            return $sessionData;
        }
    }

?>