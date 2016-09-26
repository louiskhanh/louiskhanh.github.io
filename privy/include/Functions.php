<?php

function checkFolder($name){
    global $DB;
    try {
        $stmt = $DB->prepare("SELECT * FROM `" . DBN . "`.`Directories` WHERE Name = :name");
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        if(!empty($data)) return true;
        else return false;
    } catch (PDOException $e) {
        $message = 'Message: ' . $e->getMessage();
    }
}

function getFileType($ext){

    switch ($ext){
        case 'jpg':
            return '/img/File/camera.png';
        case 'jpeg':
            return '/img/File/camera.png';
        case 'gif':
            return '/img/File/camera.png';
        default:
            return '/img/File/File-512.png';

    }
}

function getPath($id){
    global $DB;
    try {
        $stmt = $DB->prepare("SELECT Path FROM `" . DBN . "`.`Directories` WHERE DirID = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        return $data->Path;
    } catch (PDOException $e) {
        $message = 'Message: ' . $e->getMessage();
    }
}

function makeDir($path){
    $folder = ROOT.'/media/'.$path;
    if (!is_dir(ROOT.'/media/'.$path)) {
        mkdir($folder, 0777, true);
    }
    return $path;
}

function getUser($id){
    global $DB;
    try {
        $stmt = $DB->prepare("SELECT * FROM `" . DBN . "`.`Users` WHERE ID = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        $message = 'Message: ' . $e->getMessage();
    }
}
function getListCategory($id = null){
    global $DB;
    try {
        $stmt = $DB->prepare("SELECT * FROM `" . DBN . "`.`Blog_Categories` WHERE Status = 1");
        $stmt->execute();
        $data =$stmt->fetchAll(PDO::FETCH_OBJ);
        $content = '';
        $selected = '';
        if(!empty($data)) foreach ($data as $item){
            if($item->ID == $id) $selected = 'selected';
            $content .= '<option '.$item->ID.' '.$selected.'>'.$item->Name.'</option>';
        }
        return $content;
    } catch (PDOException $e) {
        $message = 'Message: ' . $e->getMessage();
    }
}
function getCategory($id){
    if($id != 0){
        global $DB;
        try {
            $stmt = $DB->prepare("SELECT * FROM `" . DBN . "`.`Blog_Categories` WHERE ID = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            $message = 'Message: ' . $e->getMessage();
        }
    }
}
function uploadFile($image,$folder = ''){
    if($image['name'] != NULL){

        if($image['size'] > 5048576){
            die("Size <= 5mb");
        }else{
            $path = ROOT."/media".$folder;
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $tmp_name = $image['tmp_name'];
            $name = $image['name'];
            // Upload file
            move_uploaded_file($tmp_name,$path.'/'.$name);
            return $name;
        }
    }
}
?>