<?   
    $documentRoot = $_SERVER["DOCUMENT_ROOT"];
    $config = array(
        "db" => array(
            "dbname" => "thegradedb",
            "username" => "gradr",
            "password" => "ThEgRadEaccEss27",
            "host" => "localhost:3306"
        ),
        "urls" => array(
            "baseUrl" => "localhost"
        ),
        "paths" => array(
            "resources" => array(
                "class" => $documentRoot . "/resources/classes",
                "interface" => $documentRoot . "/resources/interfaces",
                "module" => $documentRoot . "/resources/modules",
                "template" => $documentRoot . "/resources/templates"
            ),
            "controller" => $documentRoot . "/ctrl",
            "view" => $documentRoot . "/view"
        )        
    );

    //Error reporting
    ini_set("error_reporting", "true");
    error_reporting(E_ALL|E_STRCT);
?>