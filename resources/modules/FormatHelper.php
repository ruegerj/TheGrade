<?
    class FormatHelper
    {
        /**
         * escapes user-input to avoid XSS-attacks using htmlspecialchars()
         * @param $values associative array of user inputs
         */
        public static function sanitize(array $values) : array
        {
            foreach ($values as $key => $value) {
                $values[$key] = htmlspecialchars($value);
            }
            return $values;
        }
    }
?>