<?
    class TemplateHelper
    {
        /**
        *    renders a view
        *    @param contentFile (string)
        *    @param renderDefault (bool)
        *    @param variables (array:mixed)
        */
        public static function renderFileInTemplate($contenFile, $renderDefault = true, $variables = array()) 
        {        
            //get config via $GLOBALS
            $contentFilePath = $GLOBALS["config"]["paths"]["view"] . "/" . $contenFile;
    
            //store vars in current scope
            if (count($variables) > 0)
            {
                foreach ($variables as $key => $value) {
                    if (strlen($key) > 0) {
                       ${$key} = $value;
                    }
                }
            }
            //page exists ?
            if (file_exists($contentFilePath)) {
                if ($renderDefault === true) {
                    require_once($GLOBALS["config"]["paths"]["resources"]["template"] . "/header.php");
                }
                
                require_once($contentFilePath);            
        
                if ($renderDefault === true) {
                    require_once($GLOBALS["config"]["paths"]["resources"]["template"] . "/footer.php");
                }
                
            } else {
                //render error-page
                renderErrorPage("404", "Requested page doesn't exist", "File: " . $contenFile . " not found");
            }
    
        }
    
        public static function renderErrorPage($code = "500", $message = "Something went wrong", $exception)
        {
            require_once(realpath($GLOBALS["config"]["paths"]["view"] . "/ErrorView.php"));
        }
    }

?>