<?php
require '../_base.php';

$id = req('id');
$stm = $_db->prepare('SELECT * FROM user_profile WHERE user_id = ?');
$stm->execute([$id]);
$p = $stm->fetch() ?? '';

$stm = $_db->prepare('SELECT * FROM user WHERE id = ?');
$stm->execute([$id]);
$u = $stm->fetch();

include "../_head.php";
?>

<div class="main-content2">

<h1>Details</h1>

<table> 
        <tr>
            <th>Id</th>
            <td><?= $u -> id ?></td>
        <tr>
            <th>Username</th>
            <td><?= $u -> username ?></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><?= $p -> name ?></td>
        </tr>
        <tr>
            <th>Gender</th>
            <td><?= $p -> gender ?></td>
        </tr>
        <tr>
            <th>Date of Birth</th>
            <td><?= $p -> date_of_birth ?></td>
        </tr>
            <th>Email</th>
            <td><?= $u -> email ?></td>
        </tr>
        <tr>
            <th>Photo</th>
            <td><img src="../uploads/<?= $p -> photo ?>"</td>
        </tr>
            
</table>

<button  class = "register-btn"     data-get="update.php?id= <?=$u->id?>">Update</button>
</div>

