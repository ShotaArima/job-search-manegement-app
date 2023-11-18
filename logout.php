<?php
    session_start(); // セッションの開始

    // セッション破棄
    session_destroy();

    // セッション変数の削除
    unset($_SESSION['user_id']);

    // リダイレクトなどの処理
    header('Location: index.php');
    exit;
?>
