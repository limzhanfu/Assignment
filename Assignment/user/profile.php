<?php
require "../_base.php";

//$id = req('id');
//$stm = $_db->prepare("SELECT email FROM user WHERE id = ?");
//$stm->execute($id);
//$email = $stm->fetch();

$_genders = [
    'F' => 'Female',
    'M' => 'Male',
    'O' => "Other",
];

if(is_post()){
    $name = req("name");
    $gender = req("gender");
    $date = req("date");

    if($name == ''){
        $_err["name"]="Cannot be Empty";
    }
    
    if($gender == ''){
        $_err["gender"]="Cannot be Empty";
    }
    if($date == ''){
        $_err["date"] = "Cannot be Empty";
    }

    if (!$_err) {
        temp('info', 'Save');
    }
}


include '../_head.php'; ?>
<div class = "container-profile">

<div class = "sidebar-border">
<div class="Profile">
<h1>My Account</h1>
<a href="">Profile</a>
<a href="">Addresses</a>
<a href="">Change Password</a>
<a href="">Privacy Settings</a>
</div>
</div>

<div class = "main-content">

<h1>profile</h1>
<form method="post">
<table>
    <tr>
        <td>
            Name
        </td>
        <td>
            <?= html_text("name", "maxlength = '50'");?>
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
            email address
        </td>
    </tr>
    <tr>
        <td>
            Gender
        </td>
        <td>
            <?= html_radios('gender',$_genders)?>
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
            <input type="date" id = "date" name = "date">
        </td>
    </tr>
    <tr>
        <td></td>
        <td><?=err("date")?></td></tr>
    <tr>
    <tr>    
        <td>
            <button type="submit">Save</button>
        </td>
    </tr>
</table>
</form>

</div>




