<?php 
require "../_base.php";

$arr = $_db->query("SELECT * FROM user WHERE role!= 'admin'" )->fetchAll();

include "../_head.php";
?>


<table>
    <tr>
        <th>Id</th>
        <th>Email</th>
        <th>Name</th>
    </tr>
    
    <?php foreach($arr as $s){?>
        <tr>
            <td><?= $s -> id ?></td>
            <td><?= $s -> email ?></td>
            <td><?= $s -> name ?></td>
        </tr>    
        
    <?php } ?>


</table>
