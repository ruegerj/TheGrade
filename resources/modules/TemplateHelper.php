<?
    /**
    *    renders a view
    *    @param contentFile (string)
    *    @param renderDefault (bool)
    *    @param variables (array:mixed)
    */
    function renderFileInTemplate($contenFile, $renderDefault = true, $variables = array()) 
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
        if ($renderDefault === true) {
            require_once($GLOBALS["config"]["paths"]["resources"]["template"] . "/header.php");
        }
        
        //page exists ?
        if (file_exists($contentFilePath)) {
            require_once($contentFilePath);
        } else {
           //redirect to error page             
           require_once($GLOBALS["config"]["paths"]["resources"]["template"] . "/error.php");
        }

        if ($renderDefault === true) {
            require_once($GLOBALS["config"]["paths"]["resources"]["template"] . "/footer.php");
        }
        
    }
?>