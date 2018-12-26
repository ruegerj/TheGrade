<?   
    $documentRoot = $_SERVER["DOCUMENT_ROOT"];
    $config = array(
        "db" => array(
            "dbname" => "thegradedb",
            "username" => "gradr",
            "password" => "ThEgRadEaccEss27",
            "host" => "localhost",
            //root account is used to connect to the db-server for the first time to set up the database
            "rootUser" => "root",
            "rootPassword" => ""
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
            "lib" => array(
                "crypto" => $documentRoot . "/resources/lib/CryptoLib-master/src"
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
        ),
        "cookie" => array(
            "remember" => "REMEBER_ME"
        ),
        "autologout" => array(
            "time" => 30
        ),
        "validate" => array(
            "email" => array(
                "pattern" => "/^(([^<>()\[\]\\.,;:\s@']+(\.[^<>()\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/",                
            ),
            "name" => array(
                "max" => 30,
                "min" => 3
            ),
            "prename" => array(
                "max" => 30,
                "min" => 3
            ),
            "password" => array(
                "max" => 50,
                "min" => 8
            ),   
            "title" => array(
                "max" => 50,
                "min" => 1
            ),
            "description" => array(
                "max" => 750,
                "min" => 0
            ),
            "grading" => array(
                "max" => 500,
                "min" => 1
            ),
            "rememberMeCookie" => array(
                "timespan" => 7 //in days
            )
        )   
    );

    //Error reporting
    ini_set("error_reporting", "true");
    error_reporting(E_ALL|E_STRCT);
?>