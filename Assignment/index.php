<?php
require "_base.php";


include '_head.php'; ?>
<div class="nav-right">
            <form class="nav-search">
                <input type="text" placeholder="Laptop">
                <button>Search</button>
            </form>
        </div>
        <div class="nav-mid">
            <ul class="nav-main">
                <li><a href="/">Iphone</a></li>
                <li><a href="/">Asus</a></li>
                <li><a href="/">Logitech</a></li>
                <li><a href="/">MSI</a></li>
                <li><a href="/">REDMI</a></li>
                <li><a href="/">HUAWEI</a></li>
                <li><a href="/">MOUSE</a></li>
                <li><a href="/">CONTROLLER</a></li>
            </ul>
        </div>
    </div>
<div class="nav-box">
    <ul>
        <li>
            <a href="#">
                <img src="/img/Ip16ProMax.png" width="150px" height="100px" alt="Iphone">
                <p>Iphone 16</p>
                <span>RM 3299</span>
            </a>
        </li>
        <li>
            <a href="#">
                <img src="./img/Ip16ProMax.png" width="150px" height="100px" alt="Iphone">
                <p>Iphone 16</p>
                <span>RM 3299</span>
            </a>
        </li>
    </ul>
</div>

<div class="carousel">
    <div class="carousel-left">
        <ul>
            <li><a href="/">Laptop</a></li>
            <li><a href="/">Keyboard</a></li>
            <li><a href="/">Phone</a></li>
            <li><a href="/">Mouse</a></li>
            <li><a href="/">Headphone</a></li>
            <li><a href="/">Watch</a></li>
            <li><a href="/">Ipad</a></li>
            <li><a href="/">CPU</a></li>
            <li><a href="/">GPU</a></li>
            <li><a href="/">Memory</a></li>
        </ul>
    </div>
    <div class="carousel-right">
        <a class="buton" href="https://www.mi.com/my/">Learn More</a>
    </div>
</div>

<div class="laptop"></div>

<?php include('_foot.php');
