<?php
if(isset($_SESSION['login'])){
    $login = $_SESSION['login'];
}
else{
    header("location: index.php");
}

if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
}

if(isset($_SESSION['maker'])){
    $maker = $_SESSION['maker'];
}

if(isset($_SESSION['category'])){
    $category = $_SESSION['category'];
}

if(isset($_SESSION['cars'])){
    $cars = $_SESSION['cars'];
}

if(isset($_SESSION['order'])){
    $order = $_SESSION['order'];
}

if(isset($_SESSION['compare'])){
    $compare = $_SESSION['compare'];
}

if(isset($_SESSION['ctrcompare'])){
    $ctrcompare = $_SESSION['ctrcompare'];
}
?>