<?php
session_start();
require_once '../pdo_connect.php';
require_once '../function.php';

if (isset($_SESSION['id'])) {
    $id = $_REQUEST['id'];

    $messages = $dbh->prepare('SELECT * from posts WHERE id =?');
    $messages->execute(array($id));
    $message = $messages->fetch();

    if ($message['member_id'] == $_SESSION['id']) {
        $del = $dbh->prepare('DELETE FROM posts WHERE id =?');
        $del->execute(array($id));
    }
}

header('Location: index.php');
exit();
