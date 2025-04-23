<?php 
require "../_base.php";

auth('Admin');

$keyword = $_GET['name'] ?? '';

if ($keyword !== '') {
    $stm = $_db->prepare('SELECT * FROM user WHERE username LIKE ?');
    $stm->execute(["%$keyword%"]);
} else {
    $stm = $_db->query('SELECT * FROM user');
}

$arr = $stm->fetchAll();


include "../_head.php";
?>
<div class = "main-content2">

<h1>Member list</h1>

<form>
    <?= html_search('name') ?>
    <button>Search</button>

    <button><a href="insert.php">Insert</a></button>
</form>



<p><?= count($arr) ?> record(s)</p>

<table>
    
    <tr>
        <th>Id</th>
        <th>Email</th>
        <th>Name</th>
        <th></th>
    </tr>
    
    <?php foreach($arr as $s){?>
        <tr>
            <td><?= $s -> id ?></td>
            <td><?= $s -> email ?></td>
            <td><?= $s->username ?></td>
            <td>
                <button  data-get="detail.php?id= <?=$s->id?>">Detail</button>
                <button data-post="delete.php?id= <?=$s->id?>" data-confirm>Delete</button>
            </td>
        </tr>    
        
    <?php } ?>


</table>
</div>