<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>ログイン画面</title>
    </head>
    <body>
        <h1>ログインフォーム</h1>
        <form action="login.php" method="POST">
            ユーザ名<input type="text" name="username" value=""><br>
            パスワード<input type="password" name="password" value=""><br>
            <input type="submit" name="login" value="ログイン">
        </form>
        <a href="signin.php">新規登録はこちら</a>
</html>