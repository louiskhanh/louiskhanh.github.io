<?php
if (!$_SESSION["Admin"] == "yes") {
    die(header("Location: /admin/login"));
}
$message = '';
//Check CSRF TOKEN
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = base64_encode(openssl_random_pseudo_bytes(32));
}

if (isset($_GET['id']) && $_GET['act'] == 'edit' && !empty($_GET['id'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    try {
        $stmt = $DB->prepare("SELECT * FROM Blog_Categories WHERE ID = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $formdata = $stmt->fetch(PDO::FETCH_OBJ);
        $form = '';
        if(!empty($formdata)){
            if($formdata->Status == 1) {
                $selectActive = 'selected';
                $selectDeactive = '';
            }else{
                $selectActive = '';
                $selectDeactive = 'selected';
            }
            $form .= '
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="Name" placeholder="Name" value="'.$formdata->Name.'">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" name="Status">
                        <option value="1" '.$selectActive.'>Active</option>
                        <option value="0" '.$selectDeactive.'>Deactive</option>
                    </select>
                </div>
                <input type="hidden" name="id" value="'.$formdata->ID.'">
            ';
        }

    } catch (PDOException $e) {
        $message = 'Message Error: ' . $e->getMessage();
    }
}

if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    if (isset($_GET['id']) && $_GET['act'] == 'edit' && !empty($_GET['id'])) {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        if (empty($id)) die('Not found ID !');
        //Update record
        try {
            $stmt = $DB->prepare("UPDATE `" . DBN . "`.`Blog_Categories` SET Name = :Name, Status = :Status WHERE ID = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":Name", $_POST['Name'], PDO::PARAM_STR);
            $stmt->bindParam(":Status", $_POST['Status'], PDO::PARAM_INT);
            $id = $stmt->execute();
            if (!empty($id)) header("Location: /admin/category");
            else $message = '
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            Update unsuccessfully !
                        </div>';
        } catch (PDOException $e) {
            $message = 'Message: ' . $e->getMessage();
        }
    }else{
        //Insert record
        try {
            $stmt = $DB->prepare("INSERT INTO Blog_Categories (Name, Status) VALUES (:Name,:Status)");
            $stmt->bindParam(":Name", $_POST['Name'], PDO::PARAM_STR);
            $stmt->bindParam(":Status", $_POST['Status'], PDO::PARAM_INT);
            $id = $stmt->execute();
            if(!empty($id)) header("Location: /admin/category");
            else $message = '
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        Insert unsuccessfully !
                    </div>';
        } catch (PDOException $e) {
            $message = 'Message: ' . $e->getMessage();
        }
    }
}

if(isset($_GET['id']) && $_GET['act'] == 'delete' && !empty($_GET['id'])){
    $id = isset($_GET['id'])?$_GET['id']:'';
    if(empty($id)) die('Not found ID !');
    try {
        $stmt = $DB->prepare("DELETE FROM Blog_Categories WHERE ID = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $count = $stmt->execute();
        if(!empty($count)) header("Location: /admin/category");
        else $message = '
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        Delete unsuccessfully !
                    </div>';
    } catch (PDOException $e) {
        $message = 'Message: ' . $e->getMessage();
    }
}

//List record
try {
    $sql = "SELECT * FROM `" . DBN . "`.`Blog_Categories` ORDER BY CreatedTime DESC";
    $data = $DB->Query($sql)->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $message = 'Message Error: ' . $e->getMessage();
}

$content = '';
if(!empty($data)){
    foreach ($data as $item){
        //Check Status
        if($item->Status == 1) $status = 'Active';
        else $status = 'Deactive';

        $content .= '
            <tr>
                <td>'.$item->ID.'</td>
                <td>'.$item->Name.'</td>
                <td>'.$item->CreatedTime.'</td>
                <td>'.$status.'</td>
                <td>
                    <a class="btn btn-warning" href="/admin/category?act=edit&id='.$item->ID.'" title="Edit"> Edit</a>
                    | <a class="btn btn-danger" href="/admin/category?act=delete&id='.$item->ID.'" title="Delete"> Delete</a>
                </td>
            </tr>
        ';
    }
}else{
    $content .= '
        <tr>
            <td colspan="7"> 
                <div class="alert alert-info">
                     Data empty !
                </div>
            </td>
        </tr>
    ';
}
if(empty($form)){
    $form = '
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="Name" placeholder="Name">
        </div>
        <div class="form-group">
            <label>Status</label>
            <select class="form-control" name="Status">
                <option value="1">Active</option>
                <option value="0">Deactive</option>
            </select>
        </div>
    ';
}

$setup['{FORM}'] = isset($form)?$form:'';
$setup['{csrf_token}'] = $_SESSION['csrf_token'];
$setup['{MESSAGE}'] = $message;
$setup['{CONTENT}'] = $content;
$setup['{BODY}'] = $Project->View('/admin/category');
?>