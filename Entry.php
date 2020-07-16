
<?php

    // error_reporting(0);

    include "util/ApiSession.php";
    $routes = include "Routes.php";

    if($_GET["route"] == "api") {
        $api = new ApiSession($_POST, $routes);
        $api->executeAndPrintResponse();
    }
    else
        header('HTTP/1.0 403 Forbidden');