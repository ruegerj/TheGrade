<?
    $config = array(
        "db" => array(
            "dbname" => "theGradeDB",
            "username" => "gradr",
            "password" => "ThEgRadEaccEss27",
            "host" => "localhost"
        ),
        "urls" => array(
            "baseUrl" => "localhost"
        ),
        "paths" => array(
            "resources" => "/path/to/resources",
            "images" => array(
                "content" => $_SERVER["DOCUMENT_ROOT"] . "/images/content",
                "layout" => $_SERVER["DOCUMENT_ROOT"] . "/images/layout"
            )
        )        
    );

    //define default paths
    defined("MODULES_PATH") or define("MODULES_PATH", realpath(dirname(__FILE__) . "/modules"));
    defined("TEMPLATES_PATH") or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . "/templates"));

    //Error reporting
    ini_set("error_reporting", "true");
    error_reporting(E_ALL|E_STRCT);
?>