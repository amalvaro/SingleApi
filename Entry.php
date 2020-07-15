<?php
    include "util/ApiSession.php";
    include "util/ApiManager.php";
    $routes = include "Routes.php";

    $api = new ApiSession($_POST, $routes);
    $api->executeAndPrintResponse();