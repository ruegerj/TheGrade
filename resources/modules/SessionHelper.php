<?
    class SessionHelper
    {
        function __construct()
        {
            $this->checkSession();
        }
        /**
         * Starts session when not started yet
         */
        public function checkSession()
        {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        }
    }

?>