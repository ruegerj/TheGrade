<?
    include_once("IRequest.php");

    class Request implements IRequest
    {
        //constructor
        function __construct()
        {
            $this->bootstrapSelf();            
        }

        //creates for each key in $_SERVER a property in this class and stores the value
        private function bootstrapSelf() 
        {
            foreach ($_SERVER as $key => $value) {
                //take only the uri without the params => to enter the request handler properly
                if ($key === "REQUEST_URI") {
                    $value = explode("?", $value)[0];
                }
                $this->{$this->toCamelCase($key)} = $value;
            }
        }

        //converts a string from snake case to camel case
        private function toCamelCase($string) 
        {
            $result = strtolower($string);
            preg_match_all('/_[a-z]/', $result, $matches);

            foreach ($matches[0] as $match) {
                $c = str_replace("_", "", strtoupper($match));
                $result = str_replace($match, $c, $result);
            }

            return $result;
        }

        /**
         * gets the given arguments of the request based on the request-methods
         **/
        public function getBody()
        {
            if ($this->requestMethod == "GET") {
                $result = array();                
                foreach ($_GET as $key => $value) {
                    $result[$key] = $value;
                }
                return $result;
            }

            if ($this->requestMethod == "POST") {
                $result = array();
                foreach ($_POST as $key => $value) {
                    $result[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }

                return $result;
            }

            return $body;
        }
    }

?>