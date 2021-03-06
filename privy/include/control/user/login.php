<?php
$message = '';
if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    try {
        $username = $_POST['username'];
        $pw = $_POST['password'];
        echo $username;
        $stmt = $DB->prepare("SELECT * FROM `" . DBN . "`.`Users` WHERE Email = :email");
        $stmt->bindParam(":email", $username, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $email = $data['Email'];
        $password = $data['Password'];
        $id = $data['ID'];

        if ($username == $email) {
            if (password_verify($pw, $password)) {
                $_SESSION["UserLoggedIn"] = true;
                $_SESSION["U"] = $id;
                die(header("Location: /"));
            } else {
                $_SESSION["UserLoggedIn"] = false;
                die(header("Location: /user/login"));
            }
        } else {
            $_SESSION["UserLoggedIn"] = false;
            die(header("Location: /user/login"));
        }
    } catch (PDOException $e) {
        $message = 'Message: ' . $e->getMessage();
    }
}
$setup['{csrf_token}'] = $_SESSION['csrf_token'];
$setup['{MESSAGE}'] = $message;
?>