<?php
if (!$_SESSION["Admin"] == "yes") {
	die(header("Location: /admin/login"));
}

//Get Total User
/*$sql = "SELECT count(*) FROM `Users`";
$totalUser = $DB->prepare($sql)->execute();
$totalUser;

$setup['{totalUser}'] = $totalUser;*/

//Get total blog
try {
    $stmt = $DB->prepare("SELECT * FROM Blogs");
    $stmt->execute();
    $totalBlog = $stmt->columnCount();
    $setup['{totalBlog}'] = $totalBlog;
} catch (PDOException $e) {
    $message = 'Message Error: ' . $e->getMessage();
}


$setup['{BODY}'] = $Project->View('/admin/dashboard');
?>