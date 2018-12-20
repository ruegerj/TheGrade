<?
    require_once(realpath($GLOBALS["config"]["paths"]["resources"]["module"] . "/DBHelper.php"));

    class ApiController // doesnt implement IController => has different pattern
    {
        /**
         * checks if an email is still available / returns results json
         * @param $email requested email of user
         */
        public static function checkEmailAvailable($email) : void       
        {            
            $emailCondition = $GLOBALS["config"]["validate"]["email"]["pattern"];
            if (isset($email) && preg_match($emailCondition, $email)) {
                $dbHelper = new DBHelper();            
                $matchingEmails = $dbHelper->getMatchingEmails($email);
                if (count($matchingEmails) > 0) {
                    http_response_code(200);
                    echo '{"available": false}';
                } else {
                    http_response_code(200);
                    echo '{"available": true}';                
                }
            } else {
                http_response_code(404);
                echo '{"available": false,"response": "wrong mail format"}';
            }
        }
    }

?>