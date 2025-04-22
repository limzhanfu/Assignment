<?php
require "_base.php";

if(is_post()){
    $email = req('email');
    $password = req('password');

    if($email == ''){
        $_err['email'] = "Required";
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }
    
    if($password == ''){
        $_err['password'] = "Required";
    }

    if(!$_err){
        $stm = $_db->prepare('
        SELECT u.*, up.photo
        FROM user u
        JOIN user_profile up ON u.id = up.user_id
        WHERE u.email = ? AND u.password = SHA(?)
    ');
    
    $stm->execute([$email, $password]);
    $u = $stm->fetch();

        if($u){
         temp("info","Login Successfully");
         login($u);
        }
        else{
            $_err['password'] = "Not Matched";
        }
    }
}

include "_head.php";
?>

<div class="container">
        <div class ="register-header">
            <header><h1 >Sign in</h1></header>
        </div>

        <form method="post" class="form">
    <div class="form-row">
        <label for="email">Email</label>
        <?= html_text("email", "maxlength='30'","Enter your email") ?>
        <?php err("email") ?>
    </div>

    <div class="form-row">
        <label for="password">Password</label>
        <?= html_password("password", "maxlength='100'",'Enter your password') ?>
        <?= err("password") ?>
    </div>

    <section>
        <button type = "submit" class = "register-btn">Submit</button>
        <button type="reset" class = "register-btn">Reset</button>
    </section>

</form>

