<?php
    require_once 'dbconnect.php';

    if(isset($_POST['signin']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try
        {
            $db = connect();
            $sql = 'INSERT INTO user (user_name, user_pass) VALUES (?, ?)';
            if($db)
            {
                $stmt = $db->prepare($sql);
                $stmt->execute(array($username, $password));
                $stmt = null;
                $db = null;

                header('Location: index.php');
                exit;
            }

        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            exit;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>新規登録画面</title>
    </head>
    <body>
        <h1>新規登録</h1>
        <form action="" method="POST">
            ユーザ名<input type="text" name="username" value=""><br>
            パスワード<input type="password" name="password" value=""><br>
            <input type="submit" name="signin" value="新規登録">
        </form>
        <a href="index.php">戻る</a>
    </body>
</html>