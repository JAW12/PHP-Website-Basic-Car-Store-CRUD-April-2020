<?php
session_start();
if(isset($_SESSION['login'])){
    unset($_SESSION['login']);
}
if(isset($_SESSION['compare'])){
    $_SESSION['compare'] = [];
}
if(isset($_SESSION['ctrcompare'])){
    $_SESSION['ctrcompare'] = 0;
}
header("location: index.php");
?>