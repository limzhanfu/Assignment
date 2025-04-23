<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TT Online Shop</title>
    <link rel="shortcut icon" href="/img/TT_LOGO.png">
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/app.js"></script>
</head>
<body>

<div id="info"><?= temp('info') ?></div>


<div class="top">
    <div class="nav-left">
        <a href="/">
            <img src="/img/TT_LOGO.png" class="logo" alt="TT Logo">
        </a>
    </div>
    <div class="top-right">
        <ul class="top-rightItem">
        <li>
        <?php if ($_user != null): ?>
        <a href="/user/profile.php"><img src="/uploads/<?= $_user->photo ?>" class = "profile-img">
            <span><?= $_user->username ?></span>
        </a>
        
        </li>
        <li>
            <a href="/logout.php">Logout</a>
        </li>
        <?php else:?>
            <li>
                <a href="/login.php">Login</a>
            </li>
            <li>
                |
            </li>
            <li>
                <a href="/user/register.php">
                    Register
                </a>
            </li> 
                     
        <?php endif ?>
        <?php if($_user!== null){?>
            <?php if($_user->role == 'admin'){?>
   
            <li>
                <a href="/user/memberlist.php">Member</a>
            </li>  
            
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
</div>



