<?php
session_start();

// セッション情報を削除
$_SESSION = array();
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();
setcookie("mail", "", time() - 60 * 60 * 24 * 4, '/');

header('Location: ../index.php?logout');
exit();
