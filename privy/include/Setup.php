<?php
session_start();
include 'Mailer.php';
include 'Config.php';
include 'Database.php';
include 'Project.php';
include 'Functions.php';

if (strpos(PAGE, 'login') == false)
    if(!isset($_SESSION['UserLoggedIn']))//if($_SESSION["UserLoggedIn"] !== true)
        die(header("Location: /user/login"));

//Create CSRF_TOKEN
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = base64_encode(openssl_random_pseudo_bytes(32));
}

/** Include a default setup for all pages **/
$setup = [
    '{BASE}' => URL,
    '{AUTHOR}' => 'Stark',
    '{TITLE}' => 'Stark',
    '{DESCRIPTION}' => 'Stark',
    '{KEYWORDS}' => 'Stark',
    '{FAVICON}' => '/favicon.png',
    '{HTTPCSS}' => [
        'http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css',
    ],
    '{CSS_CMS}' => [
        "/vendor/bootstrap/css/bootstrap.min.css",
        "/vendor/metisMenu/metisMenu.min.css",
        "/css/sb-admin-2.css",
        "/vendor/morrisjs/morris.css",
    ],
    '{CSS}' => [
        "/bootstrap/bootstrap.css",
        "/bootstrap/bootstrap.min.css",
        "/css/animate.css",
        "/css/main.css",
        "/css/mobile.css",
    ],
    '{FONT_CMS}' => [
        '/vendor/font-awesome/css/font-awesome.min.css',
    ],
    '{FONT}' => [
        '/vendor/font-awesome/css/font-awesome.min.css',
    ],
    '{HTTPJS}' => [
        'http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js',
    ],
    '{JS_CMS}' => [
        "/vendor/jquery/jquery.min.js",
        "/vendor/bootstrap/js/bootstrap.min.js",
        "/vendor/metisMenu/metisMenu.min.js",
        "/vendor/raphael/raphael.min.js",
        "/vendor/morrisjs/morris.min.js",
        "/js/sb-admin-2.js",
        "/js/customs.js",
    ],
    '{JS}' => [
        "/vendor/jquery/jquery.min.js",
        "/vendor/bootstrap/js/bootstrap.min.js",
        "/js/classie.js",
        "/js/ie10-viewport-bug-workaround.js",
        "/js/customs.js",
    ],
    '{BODY}' => '404',
];
?>