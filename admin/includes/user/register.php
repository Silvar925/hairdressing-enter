<?php
require '../include.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fio = formatstr($_POST['fio']);
    $email = formatstr($_POST['email']);
    $login = formatstr($_POST['login']);
    $password = formatstr($_POST['password']);

    if (!R::findOne('user', 'login = ?', [$login])) {
        $user = R::dispense('user');
        $user->fio = $fio;
        $user->email = $email;
        $user->login = $login;
        $user->password = password_hash($password, PASSWORD_DEFAULT); 

        R::store($user);

        header('Location: /admin/');
        exit();
    } else {        
        session_start();
        $_SESSION['error'] = "Ошибка регистрации";
        header('Location: /admin/');
        exit();
    }
}
?>
