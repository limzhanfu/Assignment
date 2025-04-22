<?php
require "../_base.php";

if(is_post()){
    $password = req('password');
    $confirmPassword = req('confirmPassword');
    $newPassword = req('newPassword');

    if($password == ''){
        $_err['password'] = "Required";
    }

    if($confirmPassword == ''){
        $_err['confirmPassword'] = "Required";
    }
    else if($confirmPassword !== $newPassword){
        $_err['confirmPassword'] = "Not Matched";
    }

    if($newPassword == ''){
        $_err['newPassword'] = 'Required';
    }

    if(!$_err){
        $stm = $_db->prepare('
        SELECT *
        FROM user 
        WHERE email = ? AND password = SHA(?)
    ');
    
    $stm->execute([$_user->email, $password]);
    $u = $stm->fetch();

        if($u){
         temp("info","Password Changed Successfully");
         
         $stm = $_db->prepare('
         UPDATE user
         SET password = SHA(?)
         WHERE id = ?
                             ');
          $stm->execute([$newPassword,$_user->id]);
        }
        else{
            $_err['password'] = "Not Matched";
        }

        redirect('/');
    }
}

include '../_head.php'; 
include "../profile_layout.php";
?>
<div class = "main-content">
<form method="post" class="form" enctype="multipart/form-data">

    <div class="form-row">
        <label for="password">Password</label>
        <?= html_password("password", "maxlength='100'",'Enter your password') ?>
        <?= err("password") ?>
    </div>
    <div class="form-row">
        <label for="newPassword">New Password</label>
        <?= html_password("newPassword", "maxlength='100'",'Enter your new password') ?>
        <?= err("newPassword") ?>
    </div>
    <div class="form-row">
        <label for="confirmPassword">Confirm Password</label>
        <?= html_password("confirmPassword", "maxlength='100'",'Enter your password') ?>
        <?= err("confirmPassword") ?>
    </div>

    <section>
        <button type = "submit" class = "register-btn">Submit</button>
        <button type="reset" class = "register-btn">Reset</button>
    </section>

</form>
</div>
