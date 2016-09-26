<?php 
// Connect to the mysql db
//$DB = mysqli_connect(DBH,DBU,DBP,DBN) or die("Error " . mysqli_error($link));

define("DBH", "localhost");
define("DBU", "root");
define("DBP", "password");
define("DBN", "komal");

global $DB;
try {
    $DB = new PDO(sprintf("mysql:host=%s;dbname=%s",DBH,DBN), DBU, DBP);
    $DB->exec("set names utf8");
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $DB;
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>