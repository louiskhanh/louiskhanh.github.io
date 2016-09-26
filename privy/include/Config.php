<?php
date_default_timezone_set('America/Chicago'); 

define('ROOT', getcwd());
define('PROJECT', str_replace('/index.php', '', $_SERVER['PHP_SELF']));
define("URL", "http://".$_SERVER['HTTP_HOST'].PROJECT);

/* Database Details */


/* These are the different regexp's that determine the nice url pages */
define('UserLogin','/^user\/login\/?$/i');
define('UserLogout','/^user\/logout\/?$/i');

define('Index','/^\/?$/i');
define('Directory','/^directory\/?$/i');
define('DirectoryChild','/^directory\/([0-9-_ ]+)\/?$/i');

?>