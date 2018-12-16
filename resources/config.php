<?   
    $documentRoot = $_SERVER["DOCUMENT_ROOT"];
    $config = array(
        "db" => array(
            "dbname" => "thegradedb",
            "username" => "test", //"gradr",
            "password" => "123456789", //"ThEgRadEaccEss27",
            "host" => "localhost:3306"
        ),
        "urls" => array(
            "baseUrl" => "localhost"
        ),
        "paths" => array(
            "resources" => array(
                "backup" => $documentRoot . "/resources/backup",
                "class" => $documentRoot . "/resources/classes",
                "interface" => $documentRoot . "/resources/interfaces",
                "module" => $documentRoot . "/resources/modules",
                "template" => $documentRoot . "/resources/templates"
            ),
            "controller" => $documentRoot . "/ctrl",
            "view" => $documentRoot . "/view"
        ), 
        "session" => array(
            "user" => "USER_TOKEN",
            "forgery" => "ANTIFORGERY_TOKEN",
            "id" => "USER_ID",
            "name" => "USER_NAME",
            "prename" => "USER_PRENAME",
            "email" => "USER_EMAIL",
            "activity" => "USER_LAST_ACTIVITY"
        )   
    );

    //Error reporting
    ini_set("error_reporting", "true");
    error_reporting(E_ALL|E_STRCT);
?>