<?php
$message = '';
$id = isset($p[1])?$p[1]:0;

if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    //Insert record
    try {
        if(isset($_FILES['Path'])){
            $path = '/';
            if($id != 0) $path = getPath($id);
            $fileUpload = uploadFile($_FILES['Path'],$path);
            $path = $path.'/'.$fileUpload;
            $name = $fileUpload;
        }else{
            if(checkFolder($_POST['Name']) == true) $name = $_POST['Name'].'_'.rand(1,9);
            else $name = $_POST['Name'];
            if($id != 0) $path = getPath($id).'/'.$name;
            else $path = '/'.$name;
            $path = makeDir($path);
        }

        $stmt = $DB->prepare("INSERT INTO `" . DBN . "`.`Directories` (Name, Path, ParentID, UserID) VALUES (:Name,:Path,:ParentID,:UserID)");
        $stmt->bindParam(":Name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":Path", $path, PDO::PARAM_STR);
        $stmt->bindParam(":ParentID", $id, PDO::PARAM_INT);
        $stmt->bindParam(":UserID", $_SESSION["U"], PDO::PARAM_INT);
        $id = $stmt->execute();
        if (!empty($id)) {
            print "<script>showNotification('Insert successfully !','success')</script>";
        }
        else print "<script>showNotification('Insert unsuccessfully !','error')</script>";
    } catch (PDOException $e) {
        print "<script>showNotification('".$e->getMessage()."','error')</script>";

    }
}


//List Folder File
$page = isset($p[1])?$p[1]:1;
$data = '';
try {
    $sql = sprintf("SELECT * FROM `" . DBN . "`.`Directories` WHERE ParentID = %d  ORDER BY DirID ASC LIMIT 0,20",$id);
    $data = $DB->Query($sql)->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $message = 'Message Error: ' . $e->getMessage();
}
$content = '';

if(!empty($data)) foreach ($data as $item):
    $fileExt = getFileType($item->Path);
    $content .= '
        <li>
            <a href="/directory/'.$item->DirID.'" title="'.$item->Name.'">
                <p><img src="/img/folder.png" alt="folder"/></p>
                <p class="name">'.$item->Name.'</p>    
            </a>
        </li>
    ';
endforeach;

$setupTop['{csrf_token}'] = $_SESSION['csrf_token'];
$setup['{MESSAGE}'] = $message;

$setup['{CONTENT}'] = $content;
$setup['{DIRECTORY_TOP}'] = $Project->View('templates/stark/directorytop',$setupTop);
$setup['{BODY}'] = $Project->View('templates/stark/directory');

