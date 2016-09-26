<?php 
if($setup['{BODY}'] == '404') {
	include 'control/404.php';
}

$setup['{HTTPCSS}'] = $Project->HTTPCSS($setup['{HTTPCSS}']);
$setup['{CSS_CMS}'] = $Project->HTTPCSS($setup['{CSS_CMS}']);
$setup['{CSS}'] = $Project->HTTPCSS($setup['{CSS}']);
$setup['{FONT_CMS}'] = $Project->FONT($setup['{FONT_CMS}']);
$setup['{FONT}'] = $Project->FONT($setup['{FONT}']);
$setup['{HTTPJS}'] = $Project->HTTPJS($setup['{HTTPJS}']);
$setup['{JS}'] = $Project->HTTPJS($setup['{JS}']);
$setup['{JS_CMS}'] = $Project->HTTPJS($setup['{JS_CMS}']);

$setup['{MESSAGE}'] = '';

//Check layout Backend or Frontend
if(strpos(PAGE, 'login') !== false) echo $Project->View('user/login',$setup); //Layout login
    else if (strpos(PAGE, 'admin') !== false) echo $Project->View('admin/layouts',$setup); //Layout admin
    else{
        $oneUser = getUser($_SESSION['U']);
        $header = [
            '{User_Avatar}' => $oneUser->Avatar != ''?$oneUser->Avatar:'/img/avatar.png',
            '{User_Name}' => $oneUser->FullName,
        ];
        $setup['{HEADER}'] = $Project->View('templates/stark/header',$header);
        $setup['{FOOTER}'] = $Project->View('templates/stark/footer');
        echo $Project->View('templates/stark/layouts',$setup);//Layout frontend
    }
?>