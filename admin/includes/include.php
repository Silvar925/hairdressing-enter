<?php 
session_start();
require 'rb/rb.php';

R::setup( 'mysql:host=localhost; dbname=root', 'root', '');

if (!R::testConnection()) {
    exit('Нет подключения к БД');
}

function formatstr($str){
    $str = trim($str);
    $str = stripslashes($str);
    $str = htmlspecialchars($str);
    return $str;
}

?>
