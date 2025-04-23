<?php
include '../_base.php';



$_db->query('DELETE FROM token WHERE expired < NOW()');

$id = req('id');


if (!is_exists($id,'token','token_id')) {
    temp('info', 'Invalid token. Try again');
    redirect('/');
}

if (is_post()) {
    $password = req('password');
    $confirm  = req('confirm');


    if ($password == '') {
        $_err['password'] = 'Required';
    }
    else if (strlen($password) < 5 || strlen($password) > 100) {
        $_err['password'] = 'Between 5-100 characters';
    }

    if ($confirm == '') {
        $_err['confirm'] = 'Required';
    }
    else if (strlen($confirm) < 5 || strlen($confirm) > 100) {
        $_err['confirm'] = 'Between 5-100 characters';
    }
    else if ($confirm != $password) {
        $_err['confirm'] = 'Not matched';
    }

    if (!$_err) {

        $stm = $_db->prepare('
            UPDATE user
            SET password = SHA1(?)
            WHERE id = (SELECT user_id FROM token WHERE token_id  = ?);

            DELETE FROM token WHERE token_id = ?;
        ');
        $stm->execute([$password,$id,$id]);

        temp('info', 'Record updated');
        redirect('/login.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'User | Reset Password';
include '../_head.php';
?>
<div class = "container">
    <div class="form-row">
<form method="post" class="form">
    <label for="password">Password</label>
    <?= html_password('password', 'maxlength="100"') ?>
    <?= err('password') ?>

    <label for="confirm">Confirm</label>
    <?= html_password('confirm', 'maxlength="100"') ?>
    <?= err('confirm') ?>

    <section>
        <button class = "register-btn">Submit</button>
        <button type="reset" class = "register-btn">Reset</button>
    </section>
</form>
</div>
</div>

<?php
include '../_foot.php';