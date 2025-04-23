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
    else if (!is_unique($email, 'user', 'username')) {
        $_err['username'] = 'Existed';
    }
    

    $f = get_file('photo'); 

    // Validate: photo (file) 
    if ($f == null) {
        $photo = 'default.jpg';
    }
    else if (!str_starts_with($f->type,'image/')) { 
        $_err['photo'] = 'Must be image';
    }
    else if ($f->size > 1*1024*1024) { 
        $_err['photo'] = 'Maximum 1MB';
    }

    if (!$_err) {
       
        if($f !== null) {
            $photo = uniqid().'.jpg';
    
            require_once 'C:\Assignment\Assignment\lib\SimpleImage.php';
            $img = new SimpleImage();
            $img->fromFile($f->tmp_name)
                ->thumbnail(200,200)
                ->toFile("../uploads/$photo",'image/jpeg');
        }
        $stm = $_db->prepare('
            INSERT INTO user (email, password, username, role)
            VALUES (?, SHA1(?), ?, "Member")
        ');

        $stm->execute([$email,$password,$username]);
       
        $user_id = $_db->lastInsertId(); 

        $stm = $_db->prepare("INSERT INTO user_profile (user_id,photo) VALUES (?,?)");
        $stm->execute([$user_id,$photo]);

        temp('info', 'Record inserted');
        redirect("memberlist.php");
    }
}

 include "../_head.php"?>
 
<div class="container">
        <div class ="register-header">
            <header><h1 >Insert</h1></header>
        </div>

        <form method="post" class="form" enctype="multipart/form-data">
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

    <div class="form-row">
        <label for="photo">Photo</label>
        <label class="upload" tabindex="0">
           <?= html_file('photo','image/*','hidden') ?>
        <img src="/img/photo.jpg">
    </label>
    <?= err('photo') ?>
    </div>

    <section>
        <button type = "submit" class = "register-btn">Submit</button>
        <button type="reset" class = "register-btn">Reset</button>
    </section>

</form>

    </div>
<?php include "../_foot.php" ?>