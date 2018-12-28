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
        "paths" => array( //paths to all dirs of the app
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
        "session" => array( //all keys used in the session
            "user" => "USER_TOKEN",
            "forgery" => "ANTIFORGERY_TOKEN",
            "id" => "USER_ID",
            "name" => "USER_NAME",
            "prename" => "USER_PRENAME",
            "email" => "USER_EMAIL",
            "activity" => "USER_LAST_ACTIVITY"      
        ),
        "cookie" => array( //all keys used for cookies
            "remember" => "REMEBER_ME"
        ),
        //time in minutes someone without remember-me cookie will be automatically logged out
        "autologout" => array( 
            "time" => 30
        ),
        "ui" => array( 
            "grade" => array(
                "goodAbove" => 5.0, //grades with this value and above will displayed green
                "mediumAbove" => 4.0, //grades with this value and above will displayed grey
                "badAbove" => 1.0 //grades with this value and above will displayed red
            )
        ),
        "validate" => array( //validation definitions for user input
            "email" => array( //email regex
                "pattern" => "/^(([^<>()\[\]\\.,;:\s@']+(\.[^<>()\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/",                
            ),
            "name" => array( //amount of chars allowed
                "max" => 30,
                "min" => 3
            ),
            "prename" => array( //amount of chars allowed
                "max" => 30,
                "min" => 3
            ),
            "password" => array( //amount of chars allowed
                "max" => 50,
                "min" => 8
            ),   
            "title" => array( //amount of chars allowed
                "max" => 50,
                "min" => 1
            ),
            "description" => array( //amount of chars allowed
                "max" => 750,
                "min" => 0
            ),
            "grading" => array( //amount of chars allowed
                "max" => 500,
                "min" => 1
            ),
            "grade" => array( //min and max value of a grade
                "max" => 6,
                "min" => 1
            ),
            "date" => array(       
                "year" => array( //min and max year of a date
                    "max" => date('Y', time()),
                    "min" => 1970
                )
            ),
            "rememberMeCookie" => array( //time in days the remember-me cookue will exist
                "timespan" => 7 
            )
        )   
    );

    //Error reporting
    ini_set("error_reporting", "true");
    error_reporting(E_ALL|E_STRCT);
?>