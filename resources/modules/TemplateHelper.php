<?
    class TemplateHelper
    {
        /**
        *    renders a view
        *    @param $contentFile (string)
        *    @param $renderDefault (bool)
        *    @param $variables (array:mixed)
        */
        public static function renderFileInTemplate(string $contenFileName, bool $renderDefault = true, array $variables = array()) : void
        {        
            //get config via $GLOBALS
            $contentFilePath = $GLOBALS["config"]["paths"]["view"] . "/" . $contenFileName;
    
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
                TemplateHelper::renderErrorPage("404", "Requested page doesn't exist", "File: " . $contenFileName . " not found");
            }
    
        }
    
        public static function renderErrorPage(string $code = "500", string $message = "Something went wrong", Exception $exception) : void
        {
            require_once(realpath($GLOBALS["config"]["paths"]["view"] . "/ErrorView.php"));
        }
    }

?>