<?
    class HashHelper
    {
        /**
         * generates an hash by the given input
         * @param $input input-string
         */
        public static function generateHash($input)
        {
            $hash = password_hash($input, PASSWORD_ARGON2I);
            return $hash;
        }

        public static function generateToken($values = array())
        {        
            $stringToHash; // string with the combined values including the current time stamp
            foreach ($values as $key => $value) {
                $stringToHash . (string)$value; 
            }
            $stringToHash . (string)getdate();
            return $this->generateHash($stringToHash);
        }
    }

?>