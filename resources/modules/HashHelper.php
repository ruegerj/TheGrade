<?
    class HashHelper
    {
        /**
         * generates an hash by the given input
         * @param $input input-string
         * @param $trim specifies if the hash-information should be removed
         */
        public static function generateHash($input, $trim = false)
        {
            $hash = password_hash($input, PASSWORD_ARGON2I);
            if ($trim === true) {
                //removes the hash-information
                $hash = explode("=", $hash)[4];
            }
            return $hash;
        }

        /**
         * generates a token with given values
         * @param $values arguments too include in token
         */
        public static function generateToken($values = array())
        {        
            $stringToHash = ""; // string with the combined values including the current time stamp
            foreach ($values as $key => $value) {
                $stringToHash . (string)$value; 
            }
            $stringToHash . (string)time();
            return HashHelper::generateHash($stringToHash, true);
        }
    }

?>