<?php

ini_set('error_reporting', 1);
error_log(E_ALL);

require realpath(dirname(dirname(dirname(__FILE__))))."/includes/env.php";

new Router($pdo);