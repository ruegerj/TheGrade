<?
    //get config
    require_once(realpath(dirname(__FILE__) . "/../config.php"));

    /**
    *    renders a view
    *    @param contentFile (string)
    *    @param renderDefault (bool)
    *    @param variables (array:mixed)
    */
    function renderFileInTemplate($contenFile, $renderDefault = true, $variables = array()) 
    {
        $contentFilePath = VIEWS_PATH . "/" . $contenFile;

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
            require_once(TEMPLATES_PATH . "/header.php");
        }
        
        //page exists ?
        if (file_exists($contentFilePath)) {
            require_once($contentFilePath);
        } else {
           //redirect to error page             
           require_once(TEMPLATES_PATH . "/error.php");
        }

        if ($renderDefault === true) {
            require_once(TEMPLATES_PATH . "/footer.php");
        }
        
    }
?>