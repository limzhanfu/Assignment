<?php require "../_base.php";

if(is_post()){
    $email = req("email");
    $password = req('password');
    $confirmPassword = req('confirmPassword');
    $username = req('username');


    if($email == ''){
        $_err['email'] = "Required";
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }
    else if (!is_unique($email, 'user', 'email')) {
        $_err['email'] = 'Existed';
    }

    if($password == ''){
        $_err['password'] = "Required";
    }

    if($confirmPassword == ''){
        $_err['confirmPassword'] = "Required";
    }
    else if($confirmPassword !== $password){
        $_err['confirmPassword'] = "Not Matched";
    }

    if($username == ''){
        $_err['username'] = "Required";
    }

    if (!$_err) {
   
        $stm = $_db->prepare('
            INSERT INTO user (email, password, name, role)
            VALUES (?, SHA1(?), ?, "Member")
        ');
        $stm->execute([$email,$password,$username]);

        temp('info', 'Record inserted');
    }
}

 include "../_head.php" ?>
 
<div class="container">
        <div class ="register-header">
            <header><h1 >Register</h1></header>
        </div>

        <form method="post" class="form">
    <div class="form-row">
        <label for="email">Email</label>
        <?= html_text("email", "maxlength='30'","Enter your email") ?>
        <?= err("email") ?>
    </div>

    <div class="form-row">
        <label for="password">Password</label>
        <?= html_password("password", "maxlength='100'",'Enter your password') ?>
        <?= err("password") ?>
    </div>

    <div class="form-row">
        <label for="confirmPassword">Confirm Password</label>
        <?= html_password("confirmPassword", "maxlength='100'",'Enter your password') ?>
        <?= err("confirmPassword") ?>
    </div>

    <div class="form-row">
        <label for="username">Username</label>
        <?= html_text("username", "maxlength='30'",'Enter your username') ?>
        <?= err("username") ?>
    </div>
    <section>
        <button type = "submit" class = "register-btn">Submit</button>
        <button type="reset" class = "register-btn">Reset</button>
    </section>

</form>

    </div>
<?php include "../_foot.php" ?>