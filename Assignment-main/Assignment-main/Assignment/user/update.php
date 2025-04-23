<?php
require "../_base.php";

$id = req('id');

function load_profile($_db, $id) {
    $stm = $_db->prepare('SELECT * FROM user_profile WHERE user_id = ?');
    $stm->execute([$id]);
    return $stm->fetch();
}

function load_account($_db,$id){
    $stm = $_db->prepare('SELECT u.*, up.photo
                            FROM user u
                            JOIN user_profile up ON u.id = up.user_id
                            WHERE u.id = ?');
    $stm->execute([$id]);
    return $stm->fetch();
}

$_genders = [
    'F' => 'Female',
    'M' => 'Male',
    'O' => "Other",
];

if (is_get()) {
    $u = load_account($_db,$id);

    if (!$u) {
        redirect('/');
    }

    $k = load_profile($_db,$id);
}


if(is_post()){
    $k = load_profile($_db,$id);
    
    $name = req("name");
    $gender = req("gender");
    $date = req("date");
    $photo = $k->photo;
   
    $f = get_file('photo');

    if($name == ''){
        $_err["name"]="Cannot be Empty";
    }
    
    if($gender == ''){
        $_err["gender"]="Cannot be Empty";
    }
    if($date == ''){
        $_err["date"] = "Cannot be Empty";
    }
    // Validate: photo (file) --> optional
    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['photo'] = 'Must be image';
        }
        else if ($f->size > 1 * 1024 * 1024) {
            $_err['photo'] = 'Maximum 1MB';
        }
    }

    if (!$_err) {
        
        if ($f) {
            if($photo != "default.jpg"){
                unlink("../uploads/$photo");
            }
            $photo = save_photo($f, '../uploads');
        }

        $stm = $_db->prepare('
        UPDATE user_profile
        SET name = ?,gender = ?,date_of_birth = ?,photo = ?
        WHERE user_id = ?
                            ');
         $stm->execute([$name,$gender,$date,$photo,$id]);

        temp('info', 'Save');

        $k = load_profile($_db,$id);
        $u = load_account($_db,$id);

    }
}


include '../_head.php'; 
?>

<div class = "main-content">

<h1>Update</h1>
<form method="post" enctype="multipart/form-data">
<table>
    <tr>
        <td>
            Name
        </td>
        <td>
            <?= html_text("name", "maxlength = '50'",'',$k->name??"");?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td><?=err("name")?></td></tr>
    <tr>
        <td>
            Email
        </td>
        <td>
            <?= $u->email?>
        </td>
    </tr>
    <tr>
        <td>
            Gender
        </td>
        <td>
            <?= html_radios('gender',$_genders,'false', $k->gender)?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td><?=err("gender")?></td></tr>
    <tr>
    <tr>
        <td>
            Date of Birth
        </td>
        <td>
            <input type="date" id="date" name="date" value="<?= htmlspecialchars($k->date_of_birth ?? '') ?>"> 
        </td>
    </tr>
    <tr>
        <td></td>
        <td><?=err("date")?></td>
    </tr>
    <tr>
        <td><label for="photo">Photo</label></td>
        <td> 
            <label class="upload" tabindex="0">
            <?= html_file('photo', 'image/*', 'hidden') ?>
            <img src="../uploads/<?= $photo ?>">
            
            </label>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <?= err('photo') ?>
        </td>
        
    </tr>
    <tr>    
        <td>
            <button type="submit">Save</button>
        </td>
    </tr>
</table>
</form>

</div>






