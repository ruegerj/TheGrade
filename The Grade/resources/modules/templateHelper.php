<?
    //get config
    require_once(realpath(dirname(__FILE__) . "/../config.php"));

    function renderFileInTemplate($contenFile, $variables = array()) 
    {
        $contentFilePath = TEMPLATES_PATH . "/" . $contenFile;

        //store vars in current scope
        if (count($variables) > 0)
        {
            foreach ($variables as $key => $value) {
                if (strlen($key) > 0) {
                   ${$key} = $value;
                }
            }
        }

        require_once(TEMPLATES_PATH . "/header.php");
        
        //page exists ?
        if (file_exist($contentFilePath)) {
            require_once($contentFilePath);
        } else {
           //redirect to error page
           $errorCode = "404";
           $errorMessage = "Were sorry, this site doesn't exist";
           require_once(TEMPLATES_PATH . "/error.php");
        }

        require_once(TEMPLATES_PATH . "/footer.php");
        
    }
?>