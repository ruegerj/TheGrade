<?
    $projectDir = "/TheGrade";
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
            "controller" => $_SERVER["DOCUMENT_ROOT"] . $projectDir . "/ctrls",
            "layout" => $_SERVER["DOCUMENT_ROOT"] . $projectDir . "/views"
        )        
    );

    //define default paths
    defined("MODULES_PATH") or define("MODULES_PATH", realpath(dirname(__FILE__) . "/modules"));
    defined("TEMPLATES_PATH") or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . "/templates"));
    defined("CTRL_PATH") or define("CTRL_PATH", realpath($_SERVER["DOCUMENT_ROOT"] . $projectDir . "/ctrl"));
    defined("VIEWS_PATH") or define("VIEWS_PATH", realpath($_SERVER["DOCUMENT_ROOT"] . $projectDir . "/view"));

    //Error reporting
    ini_set("error_reporting", "true");
    error_reporting(E_ALL|E_STRCT);
?>