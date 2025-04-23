<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $email = req('email');

 
    if ($email == '') {
        $_err['email'] = 'Required';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }
    else if (!is_exists($email, 'user', 'email')) {
        $_err['email'] = 'Not exists';
    }

   
    if (!$_err) {
     
        $stm = $_db->prepare('SELECT * FROM user WHERE email = ?');
        $stm->execute([$email]);
        $u = $stm->fetch();

       
        $id = sha1(uniqid().rand());

       
        $stm = $_db->prepare('DELETE FROM token WHERE user_id = ?');
        $stm->execute([$u->id]);
        
        $stm = $_db->prepare('
            INSERT INTO token (token_id, expired, user_id)
            VALUES (?, ADDTIME(NOW(), "00:05"), ?)
        ');
        $stm->execute([$id, $u->id]);

       
        $url = base("user/token.php?id=$id");

       
        $m = get_mail();
        $m->addAddress($u->email,$u->username);
        $m->isHTML(true);
        $m->Subject = 'Reset Password';
        $m->Body = "
            
            <p>Dear $u->username,<p>
            <h1 style='color: red'>Reset Password</h1>
            <p>
                Please click <a href='$url'>here</a>
                to reset your password.
            </p>
            <p>From, TT ONLINE</p>
        ";
        $m->send();
        temp('info', 'Email sent');
        redirect('/');
    }
}

// ----------------------------------------------------------------------------

$_title = 'User | Reset Password';
include '../_head.php';
?>
<div class = "container">
    <div class = "form-row">
<form method="post" class="form">
    <label for="email">Email</label>
    <?= html_text('email', 'maxlength="100"') ?>
    <?= err('email') ?>
    </div>

    <section>
        <button type="submit" class = "register-btn">Submit</button>
        <button type="reset" class = "register-btn">Reset</button>
    </section>
</form>
</div>

<?php
include '../_foot.php';