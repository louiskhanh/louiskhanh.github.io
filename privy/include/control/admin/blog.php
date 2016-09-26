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
        $stmt = $DB->prepare("SELECT * FROM Blogs WHERE ID = :id");
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
                    <label>Title</label>
                    <input type="text" class="form-control" name="Title" placeholder="Title" value="'.$formdata->Title.'">
                </div>
                <div class="form-group">
                    <label>Keywords</label>
                    <input type="text" class="form-control" name="Keywords" placeholder="Keywords" value="'.$formdata->Keywords.'">
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" class="form-control" name="Image">
                    <img src="/uploads/'.$formdata->Image.'" width="100" style="padding: 5px 0;">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="Description">'.$formdata->Description.'</textarea>
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea class="form-control editor" name="Content" rows="8">'.$formdata->Content.'</textarea>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-control" name="CateID">
                        '.getListCategory($formdata->CateID).'
                    </select>
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
            $stmt = $DB->prepare("UPDATE `" . DBN . "`.`Blogs` SET Title = :Title, Description = :Description, Keywords = :Keywords,Image = :Image, Content = :Content, UpdatedTime = :UpdatedTime , Status = :Status WHERE ID = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":Title", $_POST['Title'], PDO::PARAM_STR);
            $stmt->bindParam(":Description", $_POST['Description'], PDO::PARAM_LOB);
            $stmt->bindParam(":Keywords", $_POST['Keywords'], PDO::PARAM_STR);
            $stmt->bindParam(":Image", uploadImage($_FILES['Image']));
            $stmt->bindParam(":Content", $_POST['Content'], PDO::PARAM_LOB);
            $stmt->bindParam(":UpdatedTime", date('Y-m-d H:i:s'));
            $stmt->bindParam(":Status", $_POST['Status'], PDO::PARAM_INT);
            $id = $stmt->execute();
            if (!empty($id)) header("Location: /admin/blog");
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
            $stmt = $DB->prepare("INSERT INTO Blogs (Title, Description, Keywords, Image, Content, UserID, Status) VALUES (:Title,:Description,:Keywords,:Image,:Content,:UserID,:Status)");
            $stmt->bindParam(":Title", $_POST['Title'], PDO::PARAM_STR);
            $stmt->bindParam(":Description", $_POST['Description'], PDO::PARAM_LOB);
            $stmt->bindParam(":Keywords", $_POST['Keywords'], PDO::PARAM_STR);
            $stmt->bindParam(":Image", uploadImage($_FILES['Image'],$formdata->Image));
            $stmt->bindParam(":Content", $_POST['Content'], PDO::PARAM_LOB);
            $stmt->bindParam(":UserID", $_SESSION["UserID"], PDO::PARAM_INT);
            $stmt->bindParam(":Status", $_POST['Status'], PDO::PARAM_INT);
            $id = $stmt->execute();
            if(!empty($id)) header("Location: /admin/blog");
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
        $stmt = $DB->prepare("DELETE FROM Blogs WHERE ID = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $count = $stmt->execute();
        if(!empty($count)) header("Location: /admin/blog");
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
    $sql = "SELECT * FROM `" . DBN . "`.`Blogs` ORDER BY CreatedTime DESC";
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
        $userData = getUser($item->UserID);
        $categoryData = getCategory($item->CateID);
        $cateName = isset($categoryData->Name)?$categoryData->Name:'';
        $content .= '
            <tr>
                <td>'.$item->ID.'</td>
                <td>'.$item->Title.'</td>
                <td>'.$item->CreatedTime.'</td>
                <td>'.$userData->FullName.'</td>
                <td>'.$cateName.'</td>
                <td>'.$status.'</td>
                <td>
                    <a class="btn btn-warning" href="/admin/blog?act=edit&id='.$item->ID.'" title="Edit"> Edit</a>
                    | <a class="btn btn-danger" href="/admin/blog?act=delete&id='.$item->ID.'" title="Delete"> Delete</a>
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
            <label>Title</label>
            <input type="text" class="form-control" name="Title" placeholder="Title">
        </div>
        <div class="form-group">
            <label>Keywords</label>
            <input type="text" class="form-control" name="Keywords" placeholder="Keywords">
        </div>
        <div class="form-group">
            <label>Image</label>
            <input type="file" class="form-control" name="Image">
            <img src="/uploads/no-image.png" width="100" style="padding: 5px 0;">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" name="Description"></textarea>
        </div>
        <div class="form-group">
            <label>Content</label>
            <textarea class="form-control editor" name="Content" rows="8"></textarea>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select class="form-control" name="CateID">
                '.getListCategory().'
            </select>
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

$setup['{FORM}'] = $form;
$setup['{csrf_token}'] = $_SESSION['csrf_token'];
$setup['{MESSAGE}'] = $message;
$setup['{CONTENT}'] = $content;
$setup['{BODY}'] = $Project->View('/admin/blog');
?>